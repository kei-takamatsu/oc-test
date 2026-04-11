const { app, BrowserWindow, session } = require('electron');

app.whenReady().then(async () => {
  const ses = session.fromPartition('persist:sns');
  
  // Try to load an Instagram post JSON endpoint
  const win = new BrowserWindow({ show: true, webPreferences: { session: ses } });
  
  // This is a known public reel URL
  win.loadURL('https://www.instagram.com/p/DBk4Q_ozmP_/?__a=1&__d=dis');
  
  win.webContents.on('did-finish-load', async () => {
    try {
      const text = await win.webContents.executeJavaScript('document.body.innerText');
      console.log("JSON DATA:", text.substring(0, 1000));
      app.quit();
    } catch(e) {
      process.exit(1);
    }
  });
});
