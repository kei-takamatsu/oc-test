const { app, BrowserWindow, session } = require('electron');

app.whenReady().then(async () => {
  const ses = session.fromPartition('persist:sns');
  
  // Try to load FB
  const win = new BrowserWindow({ show: true, width: 800, height: 600, webPreferences: { session: ses } });
  
  // Set User Agent
  win.webContents.userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";
  
  win.loadURL('https://www.facebook.com/permalink.php?story_fbid=pfbid0pW2YCSGod25CAGHzRmdxPBuCYcm3bGgAw7Lkh1fPXrxkCwHgCdB7x5Fip76r621hl&id=100080366795392');
  
  win.webContents.on('did-finish-load', async () => {
    try {
      await new Promise(r => setTimeout(r, 4000));
      const text = await win.webContents.executeJavaScript('document.body.innerText');
      const fs = require('fs');
      fs.writeFileSync('fb_debug.txt', text);
      console.log("FB Output written. Length:", text.length);
      app.quit();
    } catch(e) {
      console.log(e);
      process.exit(1);
    }
  });
});
