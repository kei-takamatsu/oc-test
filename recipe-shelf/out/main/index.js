"use strict";
const electron = require("electron");
const path = require("path");
const utils = require("@electron-toolkit/utils");
const Database = require("better-sqlite3");
const fs = require("fs");
const supabaseJs = require("@supabase/supabase-js");
const dotenv = require("dotenv");
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
const dotenv__namespace = /* @__PURE__ */ _interopNamespaceDefault(dotenv);
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
  },
  deleteSetting: (key) => {
    db.prepare("DELETE FROM settings WHERE key = ?").run(key);
  }
};
dotenv__namespace.config({ path: path.join(electron.app.getAppPath(), "../../.env") });
const supabaseUrl = process.env.SUPABASE_URL || "https://dehievtwhwvxcqwyouhy.supabase.co";
const supabaseKey = process.env.SUPABASE_ANON_KEY || "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRlaGlldnR3aHd2eGNxd3lvdWh5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzU5MjUxNzQsImV4cCI6MjA5MTUwMTE3NH0.j-pLzU_6oFfDukNLJKMDtBRqI8WUu7mMbQLDqQiZ9MA";
const customStorage = {
  getItem: (key) => {
    return dbService.getSetting(key) || null;
  },
  setItem: (key, value) => {
    dbService.setSetting(key, value);
  },
  removeItem: (key) => {
    dbService.deleteSetting(key);
  }
};
const supabase = supabaseJs.createClient(supabaseUrl, supabaseKey, {
  auth: {
    persistSession: true,
    autoRefreshToken: true,
    storageKey: "recipe-shelf-auth",
    storage: customStorage
  }
});
const authService = {
  signIn: async (email, password) => {
    const { data, error } = await supabase.auth.signInWithPassword({ email, password });
    if (error) throw error;
    return data;
  },
  signUp: async (email, password) => {
    const { data, error } = await supabase.auth.signUp({ email, password });
    if (error) throw error;
    return data;
  },
  signOut: async () => {
    const { error } = await supabase.auth.signOut();
    if (error) throw error;
  },
  getSession: async () => {
    const { data: { session } } = await supabase.auth.getSession();
    if (session) {
      const expiresAt = session.expires_at;
      const now = Math.floor(Date.now() / 1e3);
      if (expiresAt && now >= expiresAt - 60) {
        console.log("[Auth] Access token expired, refreshing...");
        const { data, error } = await supabase.auth.refreshSession({
          refresh_token: session.refresh_token
        });
        if (error) {
          console.error("[Auth] Refresh failed:", error.message);
          return null;
        }
        console.log("[Auth] Session refreshed successfully");
        return data.session;
      }
      return session;
    }
    return null;
  }
};
const storageService = {
  uploadImage: async (localFilePath, filename) => {
    const buffer = fs.readFileSync(localFilePath);
    const { data: { session } } = await supabase.auth.getSession();
    if (!session) throw new Error("Must be logged in to upload images");
    const filePath = `${session.user.id}/${filename}`;
    const { error } = await supabase.storage.from("recipe-images").upload(filePath, buffer, {
      contentType: "image/jpeg",
      upsert: true
    });
    if (error) throw error;
    const { data } = supabase.storage.from("recipe-images").getPublicUrl(filePath);
    return data.publicUrl;
  }
};
function toCamel(row) {
  return {
    id: row.id,
    title: row.title,
    sourceUrl: row.source_url,
    imageUrl: row.image_url,
    imageLocalPath: row.image_local_path,
    ingredients: typeof row.ingredients === "string" ? row.ingredients : JSON.stringify(row.ingredients || []),
    instructions: typeof row.instructions === "string" ? row.instructions : JSON.stringify(row.instructions || []),
    description: row.description,
    prepTime: row.prep_time,
    cookTime: row.cook_time,
    servings: row.servings,
    rating: row.rating,
    notes: row.notes,
    sortOrder: row.sort_order ?? 0,
    createdAt: row.created_at
  };
}
function toSnake(recipe, userId) {
  return {
    user_id: userId,
    title: recipe.title,
    source_url: recipe.sourceUrl,
    image_url: recipe.imageUrl,
    image_local_path: recipe.imageLocalPath,
    ingredients: recipe.ingredients ? JSON.parse(recipe.ingredients) : [],
    instructions: recipe.instructions ? JSON.parse(recipe.instructions) : [],
    description: recipe.description,
    prep_time: recipe.prepTime,
    cook_time: recipe.cookTime,
    servings: recipe.servings,
    rating: recipe.rating || 0,
    notes: recipe.notes,
    sort_order: recipe.sortOrder ?? 0
  };
}
const cloudDbService = {
  getAllRecipes: async () => {
    let result = await supabase.from("recipes").select("*").order("sort_order", { ascending: true }).order("created_at", { ascending: false });
    if (result.error) {
      console.warn("sort_order query failed, falling back:", result.error.message);
      result = await supabase.from("recipes").select("*").order("created_at", { ascending: false });
    }
    if (result.error) {
      console.error("Failed to fetch recipes from Supabase:", result.error);
      return [];
    }
    return (result.data || []).map(toCamel);
  },
  getRecipeById: async (id) => {
    const { data, error } = await supabase.from("recipes").select("*").eq("id", id).single();
    if (error || !data) return void 0;
    return toCamel(data);
  },
  addRecipe: async (recipe) => {
    const { data: { session } } = await supabase.auth.getSession();
    if (!session) throw new Error("You must be logged in to add a recipe.");
    const insertData = toSnake(recipe, session.user.id);
    const { data, error } = await supabase.from("recipes").insert(insertData).select("id").single();
    if (error) {
      console.error("Failed to insert recipe:", error);
      throw new Error(error.message);
    }
    return data?.id;
  },
  updateRecipe: async (id, recipe) => {
    const { data: { session } } = await supabase.auth.getSession();
    if (!session) throw new Error("Not authenticated");
    const updateData = toSnake(recipe, session.user.id);
    delete updateData.user_id;
    delete updateData.id;
    const { error } = await supabase.from("recipes").update(updateData).eq("id", id);
    if (error) throw new Error(error.message);
  },
  deleteRecipe: async (id) => {
    const { error } = await supabase.from("recipes").delete().eq("id", id);
    if (error) throw new Error(error.message);
  },
  reorderRecipes: async (orderedIds) => {
    const updates = orderedIds.map(
      (id, index) => supabase.from("recipes").update({ sort_order: index }).eq("id", id)
    );
    const results = await Promise.all(updates);
    for (const { error } of results) {
      if (error) throw new Error(error.message);
    }
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
      const msg = error instanceof Error ? error.message : String(error);
      throw new Error("AI解析エラー: " + msg);
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
  electron.ipcMain.handle("get-all-recipes", () => cloudDbService.getAllRecipes());
  electron.ipcMain.handle("get-recipe", (_, id) => cloudDbService.getRecipeById(id));
  electron.ipcMain.handle("supa-login", (_, email, pw) => authService.signIn(email, pw));
  electron.ipcMain.handle("supa-signup", (_, email, pw) => authService.signUp(email, pw));
  electron.ipcMain.handle("supa-logout", () => authService.signOut());
  electron.ipcMain.handle("supa-get-session", () => authService.getSession());
  electron.ipcMain.handle("migrate-recipes", async () => {
    const session = await authService.getSession();
    if (!session) return false;
    const localRecipes = dbService.getAllRecipes();
    let migratedCount = 0;
    for (const r of localRecipes) {
      if (r.title.includes("(Migrated)")) continue;
      try {
        let newImageLocalPath = r.imageLocalPath;
        if (r.imageLocalPath && !r.imageLocalPath.startsWith("http")) {
          const localPath = path.join(imagesPath, r.imageLocalPath);
          const publicUrl = await storageService.uploadImage(localPath, r.imageLocalPath);
          newImageLocalPath = publicUrl;
        }
        await cloudDbService.addRecipe({ ...r, imageLocalPath: newImageLocalPath });
        dbService.updateRecipe(r.id, { title: r.title + " (Migrated)" });
        migratedCount++;
      } catch (e) {
        console.error("Migration failed for recipe", r.id, e);
      }
    }
    return migratedCount;
  });
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
      if (fileName) {
        const localPath = path.join(imagesPath, fileName);
        const publicUrl = await storageService.uploadImage(localPath, fileName);
        scrapedData.imageLocalPath = publicUrl;
      }
    }
    const id = await cloudDbService.addRecipe(scrapedData);
    return cloudDbService.getRecipeById(Number(id));
  });
  electron.ipcMain.handle("add-recipe-manual", async (_, recipe) => {
    const id = await cloudDbService.addRecipe(recipe);
    return cloudDbService.getRecipeById(Number(id));
  });
  electron.ipcMain.handle("update-recipe", async (_, id, recipe) => {
    await cloudDbService.updateRecipe(id, recipe);
    return cloudDbService.getRecipeById(id);
  });
  electron.ipcMain.handle("delete-recipe", async (_, id) => {
    await cloudDbService.deleteRecipe(id);
  });
  electron.ipcMain.handle("reorder-recipes", async (_, orderedIds) => {
    await cloudDbService.reorderRecipes(orderedIds);
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
          const publicUrl = await storageService.uploadImage(localPath, fileName);
          recipeData.imageLocalPath = publicUrl;
        }
      } catch (err) {
        console.error("Failed to save base64 image", err);
      }
    }
    const id = await cloudDbService.addRecipe(recipeData);
    return cloudDbService.getRecipeById(Number(id));
  });
  createWindow();
  async function processPendingScrapeQueue() {
    try {
      const session = await authService.getSession();
      if (!session) return;
      const { data: pendings, error } = await supabase.from("recipes").select("*").eq("notes", "[PENDING_SCRAPE]").eq("user_id", session.user.id).limit(1);
      if (error) throw error;
      if (pendings && pendings.length > 0) {
        const target = pendings[0];
        console.log("[Background Scraper] Processing pending URL:", target.source_url);
        try {
          const apiKey = dbService.getSetting("gemini_api_key");
          if (!apiKey) throw new Error("API Key is not configured for SNS extraction");
          const scrapedData = await extractWithBrowserWindow(target.source_url, apiKey);
          if (scrapedData.imageUrl) {
            const fileName = await scraperService.downloadImage(scrapedData.imageUrl);
            if (fileName) {
              const localPath = path.join(imagesPath, fileName);
              const publicUrl = await storageService.uploadImage(localPath, fileName);
              scrapedData.imageLocalPath = publicUrl;
            }
          }
          scrapedData.notes = null;
          await cloudDbService.updateRecipe(target.id, scrapedData);
          console.log("[Background Scraper] Successfully processed URL:", target.source_url);
        } catch (err) {
          console.error("[Background Scraper] Failed:", err);
          await cloudDbService.updateRecipe(target.id, {
            title: "❌ 取得失敗",
            description: `自動スクレイピングに失敗しました: ${err instanceof Error ? err.message : String(err)}
PCアプリから手動で追加を試してください。`,
            notes: null
            // clear marker to break loop
          });
        }
      }
    } catch (e) {
      console.error("[Background Scraper] Queue watcher error:", e);
    }
  }
  setTimeout(processPendingScrapeQueue, 2e3);
  setInterval(processPendingScrapeQueue, 15e3);
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
        for (let attempt = 0; attempt < 5; attempt++) {
          await new Promise((r) => setTimeout(r, 2e3));
          await hiddenWindow.webContents.executeJavaScript("window.scrollBy(0, 500);").catch(() => {
          });
          const coords = await hiddenWindow.webContents.executeJavaScript(`
            (() => {
              const btns = Array.from(document.querySelectorAll('*'));
              const targets = [];
              for (const btn of btns) {
                if (btn.children.length > 2) continue; 
                const t = btn.textContent ? btn.textContent.trim() : '';
                if (!t || t.length > 25) continue; 
                
                const matchKeywords = ['続きを読む', 'more', '続きを見る', 'もっと見る', 'see more', 'さらに表示', '...more', '… さらに表示'];
                const isMatch = matchKeywords.some(kw => t.toLowerCase() === kw || t.toLowerCase() === 'さらに表示' || t.includes(kw));
                
                if (isMatch) {
                  const rect = btn.getBoundingClientRect();
                  if (rect.width > 0 && rect.height > 0) {
                     targets.push({ x: rect.x + rect.width / 2, y: rect.y + rect.height / 2 });
                  }
                }
              }
              return targets;
            })();
          `).catch(() => []);
          if (coords && coords.length > 0) {
            for (const pos of coords) {
              const x = Math.round(pos.x);
              const y = Math.round(pos.y);
              hiddenWindow.webContents.sendInputEvent({ type: "mouseMove", x, y });
              hiddenWindow.webContents.sendInputEvent({ type: "mouseDown", x, y, button: "left", clickCount: 1 });
              hiddenWindow.webContents.sendInputEvent({ type: "mouseUp", x, y, button: "left", clickCount: 1 });
              await new Promise((r) => setTimeout(r, 300));
            }
          }
        }
        await hiddenWindow.webContents.executeJavaScript("window.scrollTo(0, 0);").catch(() => {
        });
        await new Promise((r) => setTimeout(r, 1e3));
        const pageData = await hiddenWindow.webContents.executeJavaScript(`
          (() => {
            const title = document.title || '';
            const ogTitle = document.querySelector('meta[property="og:title"]')?.content || '';
            const ogDesc = document.querySelector('meta[property="og:description"]')?.content || '';
            
            // CSS等で隠された全文も取得するための独自抽出関数
            function extractReadableText(node) {
              if (!node) return "";
              const ignoreTags = ['SCRIPT', 'STYLE', 'NOSCRIPT', 'SVG', 'PATH', 'IFRAME', 'META', 'LINK'];
              if (ignoreTags.includes(node.nodeName)) return "";
              if (node.nodeType === 3) {
                  return node.textContent || "";
              }
              
              const isBlock = ['DIV', 'P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'LI', 'BR', 'TR', 'ARTICLE', 'SECTION'].includes(node.nodeName);
              let text = "";
              
              for (const child of node.childNodes) {
                 text += extractReadableText(child);
              }
              
              if (isBlock) text += "\\n";
              return text;
            }

            let bodyText = extractReadableText(document.body);
            // 余分な空行を圧縮する
            bodyText = bodyText.replace(/\\n{3,}/g, '\\n\\n').trim();

            if (!bodyText || bodyText.length < 50) {
               bodyText = document.body.innerText || '';
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
