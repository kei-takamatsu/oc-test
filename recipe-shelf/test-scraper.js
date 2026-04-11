const axios = require('axios');
const cheerio = require('cheerio');

async function test() {
  try {
    const response = await axios.get('https://www.instagram.com/instagram/', {
      headers: {
        'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
      }
    });
    const $ = cheerio.load(response.data);
    console.log("TITLE:", $('title').text());
    console.log("DESC:", $('meta[property="og:description"]').attr('content'));
    console.log("IMAGE:", $('meta[property="og:image"]').attr('content'));
  } catch (e) {
    console.error(e.message);
  }
}
test();
