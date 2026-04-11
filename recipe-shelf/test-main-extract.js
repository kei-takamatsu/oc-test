const { app, BrowserWindow, session } = require('electron');

app.whenReady().then(async () => {
    const hiddenWindow = new BrowserWindow({
      width: 1200,
      height: 800,
      show: false, // 隠しウィンドウ
      opacity: 0,
      skipTaskbar: true,
      webPreferences: {
        session: session.fromPartition('persist:sns'),
        nodeIntegration: false,
        contextIsolation: true,
        backgroundThrottling: false
      }
    })

    hiddenWindow.webContents.userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";
    
    await hiddenWindow.loadURL('https://www.facebook.com/permalink.php?story_fbid=pfbid0pW2YCSGod25CAGHzRmdxPBuCYcm3bGgAw7Lkh1fPXrxkCwHgCdB7x5Fip76r621hl&id=100080366795392');

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
            const title = document.title || '';
            const ogTitle = document.querySelector('meta[property="og:title"]')?.content || '';
            const ogDesc = document.querySelector('meta[property="og:description"]')?.content || '';
            
            function extractCleanText(node) {
              if (node.nodeType === 3) return node.textContent.trim() ? node.textContent.trim() + '\\n' : ''; // TEXT_NODE
              if (node.nodeType !== 1) return ''; // ELEMENT_NODE以外は無視
              if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'SVG', 'IFRAME', 'HEADER', 'NAV'].includes(node.tagName)) return '';
              let txt = '';
              for (let child of node.childNodes) {
                txt += extractCleanText(child);
              }
              return txt;
            }

            let targetNode = document.querySelector('article') || document.querySelector('div[role="article"]');
            if (!targetNode || targetNode.innerText.length < 50) {
               targetNode = document.querySelector('main') || document.querySelector('div[role="main"]') || document.body;
            }
            
            let bodyText = extractCleanText(targetNode);
            
            if (bodyText.length < 50) {
               bodyText = document.body.innerText;
            }

            const combinedText = [
                "Page Title: " + title,
                "OG Title: " + ogTitle,
                "OG Description: " + ogDesc,
                "--- Body Text ---",
                bodyText
            ].join('\\n');

            return {
              text: combinedText,
              imageUrl: ''
            };
          })()
    `)

    const fs = require('fs');
    fs.writeFileSync('fb_main_extract_debug.txt', pageData.text);
    console.log("Extraction Done. Length:", pageData.text.length);
    app.quit();
});
