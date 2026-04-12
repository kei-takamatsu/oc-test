# 💻 複数OS（クロスデバイス）での開発環境セットアップガイド

このプロジェクト（`oc-test`）を、MacとWindowsなどの複数のPCで同期して開発を続けるための手順書です。
ソースコードの共有には**GitHub**を使用し、AIアシスタント（私）の記憶の共有には**Google Driveのシンボリックリンク**を活用します。

---

## 【準備】1台目のPC（現在開発中のMac）で行ったこと
AIの記憶データ（`~/.gemini/antigravity`）は、すでにGoogle Driveの「マイドライブ」内の `GeminiBackup` フォルダに移動し、Mac側にシンボリックリンクが作成済みです。ソースコードもGitHubへプッシュ済みです。

---

## 🚀 2台目のPC（新しいマシン）で最初に行う設定

### 1. Google Drive の同期 ☁️
他のPCにも Google Drive アプリをインストールし、クラウド上の「GeminiBackup」フォルダがローカルに完全にダウンロード（同期）されていることを確認してください。

### 2. AIの「記憶（脳みそ）」をリンクする 🚪
AIとのこれまでの会話ログやコンテキストを引き継ぐため、同期されたGoogle Driveフォルダへの「シンボリックリンク（ショートカット）」を作成します。

#### 💻 新しいPCが Windows の場合
1. 左下のスタートボタンから「cmd」と検索し、コマンドプロンプトを **「管理者として実行」** で開きます。
2. 以下のコマンドを環境に合わせて修正し、実行します。
   *(※「あなたのアカウント名」と「G:\マイドライブ」のドライブ部分は環境に合わせて変更してください)*

```cmd
mkdir "%USERPROFILE%\.gemini"
mklink /D "%USERPROFILE%\.gemini\antigravity" "G:\マイドライブ\GeminiBackup\antigravity"
```

#### 🍎 新しいPCが Mac の場合
1. アプリケーションから「ターミナル」を開きます。
2. 以下のコマンドをそのままコピーして実行します。

```bash
MY_DRIVE=$(ls -d ~/Library/CloudStorage/GoogleDrive*/マイドライブ 2>/dev/null || ls -d ~/Library/CloudStorage/GoogleDrive*/My\ Drive 2>/dev/null | head -n 1)
mkdir -p ~/.gemini
ln -s "$MY_DRIVE/GeminiBackup/antigravity" ~/.gemini/antigravity
```

### 3. ソースコードの復元（Git Clone） 📦
記憶のリンクが完了したら、通常のGitプロジェクトと同様にソースコードを引っ張ってきます。

ターミナル（またはコマンドプロンプト）で保存したいフォルダに移動し、以下を実行します：

```bash
git clone https://github.com/kei-takamatsu/oc-test.git
cd oc-test
```
その後、必要なパッケージをインストールすれば開発準備は完了です。

---

## 🎉 セットアップ完了！
新しいPCでVSCode等のエディタからこの `oc-test` フォルダを開き、AI（Gemini Agent）を立ち上げてください。
「GeminiBackup」へのリンクが正しく機能していれば、直前までの会話ログと記憶がすべて読み込まれた状態でAIが起動し、すぐに続きの作業を再開できます！
