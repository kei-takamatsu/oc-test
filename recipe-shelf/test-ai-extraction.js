const { extractRecipeFromText } = require('./out/main/ai.js');
const fs = require('fs');

async function run() {
  const text = fs.readFileSync('fb_watch_debug.txt', 'utf8');
  // I need the api key from the sqlite db or env. I'll just look up the key from the frontend or mock it if I can... 
  // Wait, I can't read the user's API key. 
}
run();
