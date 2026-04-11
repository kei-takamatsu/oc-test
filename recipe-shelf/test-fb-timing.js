const { app, BrowserWindow, session } = require('electron');

app.whenReady().then(async () => {
    const hiddenWindow = new BrowserWindow({
      width: 1200, height: 800, show: false, opacity: 0, skipTaskbar: true,
      webPreferences: { session: session.fromPartition('persist:sns') }
    });

    await hiddenWindow.loadURL('https://www.facebook.com/permalink.php?story_fbid=pfbid0pW2YCSGod25CAGHzRmdxPBuCYcm3bGgAw7Lkh1fPXrxkCwHgCdB7x5Fip76r621hl&id=100080366795392');

    const result = await hiddenWindow.webContents.executeJavaScript(`
          return new Promise((resolve) => {
            let attempts = 0;
            const timer = setInterval(() => {
              attempts++;
              const textLength = document.body.innerText.length;
              if (textLength > 150 || attempts > 20) {
                clearInterval(timer);
                resolve({attempts, textLength});
              }
            }, 500);
          });
    `);
    
    console.log("Resolved at attempt:", result.attempts, "length:", result.textLength);
    app.quit();
});
