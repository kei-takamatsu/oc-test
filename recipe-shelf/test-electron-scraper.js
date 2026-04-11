const { app, BrowserWindow } = require('electron');

app.whenReady().then(async () => {
  const hiddenWindow = new BrowserWindow({
    width: 1024,
    height: 768,
    show: true, // Show it for the test
    webPreferences: {
      partition: 'persist:sns',
      sandbox: false
    }
  });

  hiddenWindow.loadURL('https://www.instagram.com/p/DBk4Q_ozmP_/'); // A public post

  hiddenWindow.webContents.on('did-finish-load', async () => {
    console.log("Loaded.");
    setTimeout(async () => {
      try {
        const pageData = await hiddenWindow.webContents.executeJavaScript(`
          (() => {
            const title = document.title || '';
            const ogTitle = document.querySelector('meta[property="og:title"]')?.content || '';
            const ogDesc = document.querySelector('meta[property="og:description"]')?.content || '';
            
            let bodyText = document.body.innerText;
            const article = document.querySelector('article');
            if (article) {
                bodyText = article.innerText;
            }

            const imgUrl = document.querySelector('meta[property="og:image"]')?.content || '';

            return {
              text: "Page Title: " + title + "\\nOG Title: " + ogTitle + "\\nOG Desc: " + ogDesc + "\\n--- Body Text ---\\n" + bodyText,
              imageUrl: imgUrl
            };
          })()
        `);
        console.log("--------------- EXTRACTED DATA ---------------");
        console.log(pageData);
        console.log("----------------------------------------------");
        app.quit();
      } catch (err) {
        console.error(err);
        app.quit();
      }
    }, 5000);
  });
});
