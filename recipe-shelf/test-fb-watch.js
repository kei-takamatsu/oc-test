const { app, BrowserWindow, session } = require('electron');

app.whenReady().then(async () => {
    const hiddenWindow = new BrowserWindow({
      width: 1200, height: 800, show: true, // Show to see what's happening
      webPreferences: { session: session.fromPartition('persist:sns') }
    });

    // Set UA
    hiddenWindow.webContents.userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";
    
    await hiddenWindow.loadURL('https://www.facebook.com/watch/?ref=saved&v=1829184917821321');

    await hiddenWindow.webContents.executeJavaScript(`
          return new Promise((resolve) => {
            let attempts = 0;
            const timer = setInterval(() => {
              attempts++;
              const btns = Array.from(document.querySelectorAll('span, div, button, a'));
              for (const btn of btns) {
                const t = btn.textContent ? btn.textContent.trim() : '';
                if (t === '続きを読む' || t === 'more' || t === '続きを見る' || t === 'もっと見る' || t === 'See more') {
                  btn.click();
                }
              }

              const textLength = document.body.innerText.length;
              if (textLength > 150 || attempts > 20) {
                clearInterval(timer);
                resolve(true);
              }
            }, 500);
          });
    `).catch(() => {});
        
    await new Promise(r => setTimeout(r, 1500));

    const pageData = await hiddenWindow.webContents.executeJavaScript(`
          (() => {
            let bodyText = document.body.innerText;

            let imgUrl = document.querySelector('meta[property="og:image"]')?.content || '';
            if (!imgUrl || imgUrl.includes('facebook.com/images')) {
                const imgs = Array.from(document.querySelectorAll('img'));
                let maxArea = 0; let bestSrc = '';
                for(const img of imgs) {
                    const w = img.clientWidth || img.width || 0;
                    const h = img.clientHeight || img.height || 0;
                    const area = w * h;
                    if (area > maxArea && img.src && !img.src.startsWith('data:')) {
                        maxArea = area; bestSrc = img.src;
                    }
                }
                if (bestSrc) imgUrl = bestSrc;
            }

            return { text: bodyText, imageUrl: imgUrl };
          })()
    `);

    const fs = require('fs');
    fs.writeFileSync('fb_watch_debug.txt', "Image: " + pageData.imageUrl + "\n\n" + pageData.text);
    console.log("Extraction Done. Length:", pageData.text.length, "Image:", pageData.imageUrl);
    app.quit();
});
