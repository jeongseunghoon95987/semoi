const puppeteer = require('puppeteer');
const nodeFetch = require('node-fetch').default;

// Laravel API 엔드포인트
const LARAVEL_API_BASE_URL = 'http://127.0.0.1:8080'; // /api 제거

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  try {
    // 명령줄 인자 파싱
    const args = process.argv.slice(2);
    let eventSourceId = null;
    if (args.length > 0) {
      eventSourceId = args[0];
      console.log(`Crawling specific event source with ID: ${eventSourceId}`);
    } else {
      console.log('Crawling all active event sources.');
    }

    // Laravel API를 통해 event_sources 데이터 가져오기
    let eventSourcesApiUrl = `${LARAVEL_API_BASE_URL}/api/event-sources`; // /api 추가
    if (eventSourceId) {
      eventSourcesApiUrl += `?id=${eventSourceId}`;
    }

    console.log('Fetching event sources from:', eventSourcesApiUrl);
    const response = await nodeFetch(eventSourcesApiUrl);
    if (!response.ok) {
      throw new Error(`Failed to fetch event sources: ${response.statusText}`);
    }
    const eventSources = await response.json();

    if (eventSources.length === 0) {
      console.log('No active event sources found to crawl.');
      return;
    }

    for (const source of eventSources) {
      console.log(`Navigating to ${source.url} (Source ID: ${source.id})...`);
      await page.goto(source.url, { waitUntil: 'networkidle2' });

      console.log('Page loaded. Extracting data...');

      const extractedEvents = await page.evaluate((s) => {
        // 날짜 문자열을 YYYY-MM-DD HH:mm:ss 형식으로 파싱하는 헬퍼 함수
        function parseDateString(dateStr) {
          if (!dateStr) return null;

          // '년', '월', '일' 포함된 한글 날짜 처리 (예: 2025년 8월 7일)
          const koreanDateMatch = dateStr.match(/(\d{4})년 (\d{1,2})월 (\d{1,2})일/);
          if (koreanDateMatch) {
            const year = parseInt(koreanDateMatch[1]);
            const month = parseInt(koreanDateMatch[2]) - 1; // 월은 0부터 시작
            const day = parseInt(koreanDateMatch[3]);
            const date = new Date(year, month, day);
            return formatDateToISO(date);
          }

          // 'MM/DD' 형식 처리 (예: 7/10, 8/13)
          const monthDaySlashMatch = dateStr.match(/(\d{1,2})\/(\d{1,2})/);
          if (monthDaySlashMatch) {
            const currentYear = new Date().getFullYear();
            const month = parseInt(monthDaySlashMatch[1]) - 1;
            const day = parseInt(monthDaySlashMatch[2]);
            const date = new Date(currentYear, month, day);
            return formatDateToISO(date);
          }

          // '월.일' 형식 처리 (예: 8.18)
          const monthDayDotMatch = dateStr.match(/(\d{1,2})\.(\d{1,2})/);
          if (monthDayDotMatch) {
            const currentYear = new Date().getFullYear();
            const month = parseInt(monthDayDotMatch[1]) - 1;
            const day = parseInt(monthDayDotMatch[2]);
            const date = new Date(currentYear, month, day);
            return formatDateToISO(date);
          }

          // 일반적인 날짜 형식 처리 (예: YYYY.MM.DD, YYYY-MM-DD, YYYY/MM/DD)
          // 시간 정보가 없으면 자정으로 설정
          let date = new Date(dateStr.replace(/\./g, '-').replace(/\//g, '-'));

          // 유효한 날짜인지 확인
          if (isNaN(date.getTime())) {
            return null; // 유효하지 않은 날짜
          }

          return formatDateToISO(date);
        }

        // 날짜 범위 문자열을 파싱하는 헬퍼 함수 (예: 8.18 (월) ~ 12.15 (월), 7/10~8/13)
        function parseDateRange(dateRangeStr) {
          if (!dateRangeStr) return { start: null, end: null };

          // 괄호 안의 내용 (요일 정보 등) 제거
          const cleanedStr = dateRangeStr.replace(/\s*\([^)]+\)\s*/g, '').trim();

          // 'YYYY년 MM월 DD일' 형식의 날짜 범위 처리 (예: 2025년 7월 10일~2025년 8월 13일)
          const fullDateRangeMatch = cleanedStr.match(/(\d{4}년 \d{1,2}월 \d{1,2}일)~(\d{4}년 \d{1,2}월 \d{1,2}일)/);
          if (fullDateRangeMatch) {
            return {
              start: parseDateString(fullDateRangeMatch[1]),
              end: parseDateString(fullDateRangeMatch[2])
            };
          }

          // 'MM/DD~MM/DD' 형식의 날짜 범위 처리 (예: 7/10~8/13)
          const slashDateRangeMatch = cleanedStr.match(/(\d{1,2}\/\d{1,2})~(\d{1,2}\/\d{1,2})/);
          if (slashDateRangeMatch) {
            return {
              start: parseDateString(slashDateRangeMatch[1]),
              end: parseDateString(slashDateRangeMatch[2])
            };
          }

          const parts = cleanedStr.split('~').map(p => p.trim());
          let startDate = null;
          let endDate = null;

          if (parts.length === 2) {
            startDate = parseDateString(parts[0]);
            endDate = parseDateString(parts[1]);
          } else {
            // 단일 날짜인 경우
            startDate = parseDateString(cleanedStr);
            endDate = startDate;
          }

          return { start: startDate, end: endDate };
        }

        // Date 객체를 YYYY-MM-DD HH:mm:ss 형식 문자열로 변환
        function formatDateToISO(date) {
          if (!date || isNaN(date.getTime())) return null;
          const year = date.getFullYear();
          const month = String(date.getMonth() + 1).padStart(2, '0');
          const day = String(date.getDate()).padStart(2, '0');
          const hours = String(date.getHours()).padStart(2, '0');
          const minutes = String(date.getMinutes()).padStart(2, '0');
          const seconds = String(date.getSeconds()).padStart(2, '0');
          return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        const eventList = document.querySelector(s.list_selector);
        if (!eventList) return [];

        const items = Array.from(eventList.querySelectorAll(s.item_selector));

        return items.map((item, index) => {
          console.log(`Processing item ${index + 1}:`);

          const titleElement = item.querySelector(s.title_selector);
          const dateElement = item.querySelector(s.date_selector);
          const startDateElement = item.querySelector(s.start_date_selector);
          const endDateElement = item.querySelector(s.end_date_selector);
          const urlElement = item.querySelector(s.url_selector);
          const descriptionElement = item.querySelector(s.description_selector);
          const thumbnailElement = item.querySelector(s.thumbnail_selector);

          console.log('Selectors:');
          console.log('- title_selector:', s.title_selector);
          console.log('- date_selector:', s.date_selector);
          console.log('- start_date_selector:', s.start_date_selector);
          console.log('- end_date_selector:', s.end_date_selector);

          console.log('Elements found:');
          console.log('- titleElement:', !!titleElement);
          console.log('- dateElement:', !!dateElement);
          console.log('- startDateElement:', !!startDateElement);
          console.log('- endDateElement:', !!endDateElement);

          let eventUrl = null;
          if (urlElement) {
            eventUrl = urlElement.href;
            if (eventUrl && !eventUrl.startsWith('http')) {
              eventUrl = new URL(eventUrl, window.location.href).href;
            }
          }

          let thumbnailUrl = null;
          if (thumbnailElement) {
            thumbnailUrl = thumbnailElement.src;
            if (thumbnailUrl && !thumbnailUrl.startsWith('http')) {
              thumbnailUrl = new URL(thumbnailUrl, window.location.href).href;
            }
          }

          let startDate = null;
          let endDate = null;

          if (startDateElement) {
            const startDateText = startDateElement.innerText.trim();
            console.log('Start date text:', startDateText);
            startDate = parseDateString(startDateText);
            console.log('Parsed start date:', startDate);
          }
          if (endDateElement) {
            const endDateText = endDateElement.innerText.trim();
            console.log('End date text:', endDateText);
            endDate = parseDateString(endDateText);
            console.log('Parsed end date:', endDate);
          }

          // 시작일/종료일 선택자가 없으면 단일 날짜 선택자 또는 날짜 범위 파싱 시도
          if (!startDate && !endDate && dateElement) {
            const dateText = dateElement.innerText.trim();
            console.log('Raw date text:', dateText);
            const parsedRange = parseDateRange(dateText);
            console.log('Parsed range:', parsedRange);
            startDate = parsedRange.start;
            endDate = parsedRange.end;
            console.log('Final dates - start:', startDate, 'end:', endDate);
          }

          const result = {
            name: titleElement ? titleElement.innerText.trim() : null,
            description: descriptionElement ? descriptionElement.innerText.trim() : null,
            start_date: startDate,
            end_date: endDate,
            url: eventUrl,
            thumbnail_url: thumbnailUrl,
          };

          console.log('Final result:', result);
          console.log('---');

          return result;
        });
      }, source); // source만 전달

      console.log('Extracted Events:', extractedEvents);

      // 추출된 이벤트를 Laravel API로 전송
      for (const eventData of extractedEvents) {
        try {
          // 날짜 파싱이 이미 이루어졌으므로, 여기서는 추가 변환 불필요
          // 다만, null 값은 API에서 허용하지 않을 수 있으므로 필터링하거나 기본값 설정 필요
          if (!eventData.start_date || !eventData.end_date) {
            console.warn('Skipping event due to invalid date:', eventData.name);
            continue;
          }

          // event_source_id 추가
          const dataToSend = {
            ...eventData,
            event_source_id: source.id, // 현재 source의 ID를 추가
          };

          const response = await nodeFetch(`${LARAVEL_API_BASE_URL}/api/events`, { // /api/events로 수정
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend), // event_source_id가 포함된 데이터 전송
          });

          if (response.ok) {
            console.log('Event sent to Laravel API successfully:', eventData.name);
          } else {
            const errorText = await response.text();
            console.error(`Failed to send event ${eventData.name} to Laravel API:`, response.status, errorText);
          }
        } catch (apiError) {
          console.error('Error sending event to Laravel API:', apiError);
        }
      }
    }

  } catch (error) {
    console.error('Error during crawling:', error);
  } finally {
    await browser.close();
  }
})();