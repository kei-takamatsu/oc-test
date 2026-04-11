"use strict";
const electron = require("electron");
const path = require("path");
const utils = require("@electron-toolkit/utils");
const Database = require("better-sqlite3");
const fs = require("fs");
const axios = require("axios");
const cheerio = require("cheerio");
const uuid = require("uuid");
const genai = require("@google/genai");
const url = require("url");
function _interopNamespaceDefault(e) {
  const n = Object.create(null, { [Symbol.toStringTag]: { value: "Module" } });
  if (e) {
    for (const k in e) {
      if (k !== "default") {
        const d = Object.getOwnPropertyDescriptor(e, k);
        Object.defineProperty(n, k, d.get ? d : {
          enumerable: true,
          get: () => e[k]
        });
      }
    }
  }
  n.default = e;
  return Object.freeze(n);
}
const cheerio__namespace = /* @__PURE__ */ _interopNamespaceDefault(cheerio);
const userDataPath = electron.app.getPath("userData");
const dbPath = path.join(userDataPath, "recipes.sqlite");
const imagesPath = path.join(userDataPath, "recipe_images");
fs.mkdirSync(imagesPath, { recursive: true });
const db = new Database(dbPath);
db.exec(`
  CREATE TABLE IF NOT EXISTS recipes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    sourceUrl TEXT,
    imageUrl TEXT,
    imageLocalPath TEXT,
    ingredients TEXT, -- JSON
    instructions TEXT, -- JSON
    description TEXT,
    prepTime TEXT,
    cookTime TEXT,
    servings TEXT,
    rating INTEGER DEFAULT 0,
    notes TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT
  );
`);
const dbService = {
  getAllRecipes: () => {
    return db.prepare("SELECT * FROM recipes ORDER BY createdAt DESC").all();
  },
  getRecipeById: (id) => {
    return db.prepare("SELECT * FROM recipes WHERE id = ?").get(id);
  },
  addRecipe: (recipe) => {
    const info = db.prepare(`
      INSERT INTO recipes (
        title, sourceUrl, imageUrl, imageLocalPath, ingredients, 
        instructions, description, prepTime, cookTime, servings, rating, notes
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `).run(
      recipe.title,
      recipe.sourceUrl,
      recipe.imageUrl,
      recipe.imageLocalPath,
      recipe.ingredients,
      recipe.instructions,
      recipe.description,
      recipe.prepTime,
      recipe.cookTime,
      recipe.servings,
      recipe.rating || 0,
      recipe.notes
    );
    return info.lastInsertRowid;
  },
  updateRecipe: (id, recipe) => {
    const keys = Object.keys(recipe).filter((k) => k !== "id");
    const sets = keys.map((k) => `${k} = ?`).join(", ");
    const values = keys.map((k) => recipe[k]);
    db.prepare(`UPDATE recipes SET ${sets} WHERE id = ?`).run(...values, id);
  },
  deleteRecipe: (id) => {
    db.prepare("DELETE FROM recipes WHERE id = ?").run(id);
  },
  getSetting: (key) => {
    const row = db.prepare("SELECT value FROM settings WHERE key = ?").get(key);
    return row?.value;
  },
  setSetting: (key, value) => {
    db.prepare(`
      INSERT INTO settings (key, value) VALUES (?, ?)
      ON CONFLICT(key) DO UPDATE SET value = excluded.value
    `).run(key, value);
  }
};
const scraperService = {
  scrapeUrl: async (url2) => {
    try {
      const response = await axios.get(url2, {
        headers: {
          "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
        }
      });
      const $ = cheerio__namespace.load(response.data);
      const ldJsonScripts = $('script[type="application/ld+json"]');
      let recipeData = null;
      ldJsonScripts.each((_, element) => {
        try {
          const json = JSON.parse($(element).html() || "");
          const findRecipe = (obj) => {
            if (Array.isArray(obj)) {
              for (const item of obj) {
                const found = findRecipe(item);
                if (found) return found;
              }
            } else if (obj && typeof obj === "object") {
              if (obj["@type"] === "Recipe" || Array.isArray(obj["@type"]) && obj["@type"].includes("Recipe")) {
                return obj;
              }
              if (obj["@graph"]) {
                return findRecipe(obj["@graph"]);
              }
            }
            return null;
          };
          recipeData = findRecipe(json);
          if (recipeData) return false;
        } catch (e) {
        }
      });
      if (!recipeData) {
        return {
          title: $("title").text() || "Unknown Recipe",
          sourceUrl: url2,
          description: $('meta[property="og:description"]').attr("content") || ""
        };
      }
      let instructions = [];
      if (Array.isArray(recipeData.recipeInstructions)) {
        instructions = recipeData.recipeInstructions.map((step) => {
          if (typeof step === "string") return step;
          if (step.text) return step.text;
          return "";
        }).filter(Boolean);
      } else if (typeof recipeData.recipeInstructions === "string") {
        instructions = [recipeData.recipeInstructions];
      }
      let imageUrl = "";
      if (Array.isArray(recipeData.image)) {
        imageUrl = typeof recipeData.image[0] === "string" ? recipeData.image[0] : recipeData.image[0].url;
      } else if (typeof recipeData.image === "object" && recipeData.image.url) {
        imageUrl = recipeData.image.url;
      } else if (typeof recipeData.image === "string") {
        imageUrl = recipeData.image;
      }
      return {
        title: recipeData.name || $("title").text(),
        sourceUrl: url2,
        imageUrl,
        description: recipeData.description || "",
        ingredients: JSON.stringify(recipeData.recipeIngredient || []),
        instructions: JSON.stringify(instructions),
        prepTime: recipeData.prepTime,
        cookTime: recipeData.cookTime,
        servings: recipeData.recipeYield ? String(recipeData.recipeYield) : void 0
      };
    } catch (error) {
      console.error("Error scraping URL:", error);
      throw new Error("Failed to scrape the provided URL.");
    }
  },
  downloadImage: async (imageUrl) => {
    if (!imageUrl) return void 0;
    try {
      const extension = path.extname(new URL(imageUrl).pathname) || ".jpg";
      const fileName = `${uuid.v4()}${extension}`;
      const localPath = path.join(imagesPath, fileName);
      const response = await axios({
        method: "GET",
        url: imageUrl,
        responseType: "stream"
      });
      const writer = fs.createWriteStream(localPath);
      response.data.pipe(writer);
      return new Promise((resolve, reject) => {
        writer.on("finish", () => resolve(fileName));
        writer.on("error", reject);
      });
    } catch (error) {
      console.error("Error downloading image:", error);
      return void 0;
    }
  }
};
const aiService = {
  extractRecipeFromText: async (text, apiKey) => {
    if (!apiKey) {
      throw new Error("Gemini API key is not set.");
    }
    const ai = new genai.GoogleGenAI({ apiKey });
    const schema = {
      type: genai.Type.OBJECT,
      properties: {
        title: {
          type: genai.Type.STRING,
          description: "レシピのタイトル"
        },
        description: {
          type: genai.Type.STRING,
          description: "レシピの簡潔な説明（もしあれば）"
        },
        ingredients: {
          type: genai.Type.ARRAY,
          items: {
            type: genai.Type.STRING
          },
          description: "材料のリスト。"
        },
        instructions: {
          type: genai.Type.ARRAY,
          items: {
            type: genai.Type.STRING
          },
          description: "作り方・手順のリスト。順番通りに配列にすること。"
        },
        prepTime: {
          type: genai.Type.STRING,
          description: "準備にかかる時間（例: '10分'）。不明な場合は空文字"
        },
        cookTime: {
          type: genai.Type.STRING,
          description: "調理にかかる時間（例: '20分'）。不明な場合は空文字"
        },
        servings: {
          type: genai.Type.STRING,
          description: "何人前か（例: '2人分'）。不明な場合は空文字"
        }
      },
      required: ["title", "ingredients", "instructions"]
    };
    const prompt = `以下のテキストからレシピ情報を抽出し、指定されたJSON構造で返してください。
SNSの投稿文（キャプション）などが含まれている場合がありますが、レシピに必要な情報（タイトル、材料、作り方、分量、時間）だけを抜き出してください。
もし入力テキスト内に明確な「レシピのタイトル」に該当する文言がない場合は、記載されている料理の内容からふさわしい料理名を自動で判断・生成して「title」に設定してください（例: 「鶏肉のトマト煮込み」など）。
材料や作り方は、人間が読みやすいように整理してリスト化してください。

元のテキスト:
${text}
`;
    try {
      const response = await ai.models.generateContent({
        model: "gemini-2.5-flash",
        contents: prompt,
        config: {
          responseMimeType: "application/json",
          responseSchema: schema,
          temperature: 0.1
        }
      });
      const responseText = response.text;
      if (!responseText) {
        throw new Error("AI did not return any text.");
      }
      const parsedData = JSON.parse(responseText);
      return {
        title: parsedData.title || "タイトルなしのレシピ",
        description: parsedData.description || "",
        ingredients: JSON.stringify(parsedData.ingredients || []),
        instructions: JSON.stringify(parsedData.instructions || []),
        prepTime: parsedData.prepTime || void 0,
        cookTime: parsedData.cookTime || void 0,
        servings: parsedData.servings || void 0
      };
    } catch (error) {
      console.error("Error extracting recipe with Gemini:", error);
      throw new Error("Failed to extract recipe using AI.");
    }
  }
};
electron.protocol.registerSchemesAsPrivileged([
  { scheme: "recipe-image", privileges: { bypassCSP: true, stream: true } }
]);
function createWindow() {
  const mainWindow = new electron.BrowserWindow({
    width: 1100,
    height: 800,
    show: false,
    autoHideMenuBar: true,
    titleBarStyle: "hiddenInset",
    webPreferences: {
      preload: path.join(__dirname, "../preload/index.js"),
      sandbox: false
    }
  });
  mainWindow.on("ready-to-show", () => {
    mainWindow.show();
  });
  mainWindow.webContents.setWindowOpenHandler((details) => {
    electron.shell.openExternal(details.url);
    return { action: "deny" };
  });
  if (utils.is.dev && process.env["ELECTRON_RENDERER_URL"]) {
    mainWindow.loadURL(process.env["ELECTRON_RENDERER_URL"]);
  } else {
    mainWindow.loadFile(path.join(__dirname, "../renderer/index.html"));
  }
}
electron.app.userAgentFallback = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";
electron.app.whenReady().then(() => {
  utils.electronApp.setAppUserModelId("com.recipe-shelf");
  electron.protocol.handle("recipe-image", (request) => {
    const url$1 = request.url.replace("recipe-image://", "");
    const filePath = path.join(imagesPath, url$1);
    return electron.net.fetch(url.pathToFileURL(filePath).toString());
  });
  electron.app.on("browser-window-created", (_, window) => {
    utils.optimizer.watchWindowShortcuts(window);
  });
  electron.ipcMain.handle("get-all-recipes", () => dbService.getAllRecipes());
  electron.ipcMain.handle("get-recipe", (_, id) => dbService.getRecipeById(id));
  let loginWindow = null;
  electron.ipcMain.handle("open-login-window", (_, url2) => {
    if (loginWindow) {
      loginWindow.focus();
      return;
    }
    loginWindow = new electron.BrowserWindow({
      width: 800,
      height: 800,
      webPreferences: {
        partition: "persist:sns",
        sandbox: false
      }
    });
    loginWindow.loadURL(url2);
    loginWindow.on("closed", () => {
      loginWindow = null;
    });
  });
  electron.ipcMain.handle("add-recipe-from-url", async (_, url2) => {
    const isSNS = url2.includes("instagram.com") || url2.includes("tiktok.com") || url2.includes("facebook.com") || url2.includes("twitter.com") || url2.includes("x.com");
    let scrapedData;
    if (isSNS) {
      const apiKey = dbService.getSetting("gemini_api_key");
      if (!apiKey) throw new Error("API Key is not configured for SNS extraction");
      scrapedData = await extractWithBrowserWindow(url2, apiKey);
    } else {
      scrapedData = await scraperService.scrapeUrl(url2);
    }
    if (scrapedData.imageUrl) {
      const fileName = await scraperService.downloadImage(scrapedData.imageUrl);
      scrapedData.imageLocalPath = fileName;
    }
    const id = dbService.addRecipe(scrapedData);
    return dbService.getRecipeById(Number(id));
  });
  electron.ipcMain.handle("add-recipe-manual", async (_, recipe) => {
    const id = dbService.addRecipe(recipe);
    return dbService.getRecipeById(Number(id));
  });
  electron.ipcMain.handle("update-recipe", (_, id, recipe) => {
    dbService.updateRecipe(id, recipe);
    return dbService.getRecipeById(id);
  });
  electron.ipcMain.handle("delete-recipe", (_, id) => {
    dbService.deleteRecipe(id);
  });
  electron.ipcMain.handle("get-setting", (_, key) => {
    return dbService.getSetting(key);
  });
  electron.ipcMain.handle("save-setting", (_, key, value) => {
    dbService.setSetting(key, value);
  });
  electron.ipcMain.handle("add-recipe-from-text", async (_, text, dataUrl) => {
    const apiKey = dbService.getSetting("gemini_api_key");
    if (!apiKey) throw new Error("API Key is not configured");
    const recipeData = await aiService.extractRecipeFromText(text, apiKey);
    if (dataUrl && dataUrl.startsWith("data:image/")) {
      try {
        const matches = dataUrl.match(/^data:([A-Za-z-+\/]+);base64,(.+)$/);
        if (matches && matches.length === 3) {
          const extension = matches[1].split("/")[1] === "jpeg" ? "jpg" : matches[1].split("/")[1];
          const fileName = `${uuid.v4()}.${extension}`;
          const localPath = path.join(imagesPath, fileName);
          const buffer = Buffer.from(matches[2], "base64");
          fs.writeFileSync(localPath, buffer);
          recipeData.imageLocalPath = fileName;
        }
      } catch (err) {
        console.error("Failed to save base64 image", err);
      }
    }
    const id = dbService.addRecipe(recipeData);
    return dbService.getRecipeById(Number(id));
  });
  createWindow();
  electron.app.on("activate", function() {
    if (electron.BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});
async function extractWithBrowserWindow(url2, apiKey) {
  return new Promise((resolve, reject) => {
    const hiddenWindow = new electron.BrowserWindow({
      width: 1024,
      height: 768,
      show: true,
      opacity: 0,
      // 画面上は見えないようにする
      skipTaskbar: true,
      // タスクバー/Dockに出さない
      webPreferences: {
        partition: "persist:sns",
        // ログインセッションを共有
        sandbox: false,
        backgroundThrottling: false
        // 裏側でもタイマーや描写を止めない
      }
    });
    hiddenWindow.loadURL(url2);
    let timeout = setTimeout(() => {
      hiddenWindow.destroy();
      reject(new Error("Timeout loading page"));
    }, 15e3);
    hiddenWindow.webContents.on("did-finish-load", async () => {
      clearTimeout(timeout);
      try {
        await hiddenWindow.webContents.executeJavaScript(`
          return new Promise((resolve) => {
            let attempts = 0;
            const timer = setInterval(() => {
              attempts++;
              
              // 少しずつスクロールして遅延読み込みを促す
              window.scrollBy(0, 500);

              // 続きを読むボタンがあれば随時押す (FacebookやTwitter等の「もっと見る」にも対応)
              const btns = Array.from(document.querySelectorAll('span, div, button, a'));
              for (const btn of btns) {
                const t = btn.textContent ? btn.textContent.trim() : '';
                if (!t || t.length > 20) continue; // テキストが長すぎる要素は無視
                
                const matchKeywords = ['続きを読む', 'more', '続きを見る', 'もっと見る', 'see more', 'さらに表示', '...more', '… さらに表示'];
                const isMatch = matchKeywords.some(kw => t.toLowerCase().includes(kw));
                if (isMatch) {
                  try { btn.click(); } catch (e) {}
                }
              }

              // テキスト量が一定以上（150文字以上）になるか、10秒経過したら完了
              const textLength = document.body.innerText.length;
              if (textLength > 150 || attempts > 20) {
                clearInterval(timer);
                window.scrollTo(0, 0); // 最後に一番上に戻しておく
                resolve(true);
              }
            }, 500);
          });
        `).catch(() => {
        });
        await new Promise((r) => setTimeout(r, 1500));
        const pageData = await hiddenWindow.webContents.executeJavaScript(`
          (() => {
            const title = document.title || '';
            const ogTitle = document.querySelector('meta[property="og:title"]')?.content || '';
            const ogDesc = document.querySelector('meta[property="og:description"]')?.content || '';
            
            // 複雑なDOM構造に依存せず、画面上のすべての表示テキストを丸ごとGeminiに渡して判別させる
            let bodyText = document.body.innerText;
            if (!bodyText || bodyText.length < 50) {
               bodyText = document.body.textContent || '';
            }

            // 画像のフォールバック (OGPが無い場合、ページ内で「最も面積が大きい」画像を探す)
            let imgUrl = document.querySelector('meta[property="og:image"]')?.content || '';
            if (!imgUrl || imgUrl.includes('facebook.com/images') || imgUrl.includes('fbcdn.net/images')) {
                // Videoのポスター画像があれば優先的に取得
                const videoWithPoster = document.querySelector('video[poster]');
                if (videoWithPoster && videoWithPoster.getAttribute('poster')) {
                    imgUrl = videoWithPoster.getAttribute('poster') || '';
                } else {
                    const imgs = Array.from(document.querySelectorAll('img'));
                    let maxArea = 0;
                    let bestSrc = '';
                    
                    for(const img of imgs) {
                        const w = img.clientWidth || img.width || 0;
                        const h = img.clientHeight || img.height || 0;
                        const area = Number(w) * Number(h);
                        
                        if (area > maxArea && img.src && !img.src.startsWith('data:')) {
                            maxArea = area;
                            bestSrc = img.src;
                        }
                    }
                    
                    if (bestSrc) {
                        imgUrl = bestSrc;
                    }
                }
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
              imageUrl: imgUrl
            };
          })()
        `);
        hiddenWindow.destroy();
        try {
          fs.writeFileSync("debug_extracted.txt", pageData.text);
        } catch (e) {
          console.error("Failed to write debug file", e);
        }
        const recipeData = await aiService.extractRecipeFromText(pageData.text, apiKey);
        if (pageData.imageUrl) {
          recipeData.imageUrl = pageData.imageUrl;
        }
        resolve(recipeData);
      } catch (err) {
        hiddenWindow.destroy();
        reject(err);
      }
    });
  });
}
electron.app.on("window-all-closed", () => {
  if (process.platform !== "darwin") {
    electron.app.quit();
  }
});
