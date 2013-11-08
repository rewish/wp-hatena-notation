=== Plugin Name ===
Contributors: rewish
Tags: post, editor, hatena, plugin
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

はてな記法を用いて記事を書くことができます。

== Description ==

[はてなダイアリー](http://d.hatena.ne.jp/)でおなじみの`はてな記法`を用いて記事を書くことができます。

[Fork me on GitHub](https://github.com/rewish/wp-hatena-notation) :)

= サポートする記法 =

使用可能な記法については、はてな記法ライブラリ（[HatenaSyntax](https://github.com/anatoo/HatenaSyntax)）に依存しています。

詳しくは[サポートする記法 - HatenaSyntaxマニュアル](http://nimpad.jp/hatenasyntax/%E3%82%B5%E3%83%9D%E3%83%BC%E3%83%88%E3%81%99%E3%82%8B%E8%A8%98%E6%B3%95)を参照してください。

* 見出し記法
* 名前付き見出し記法
* リスト記法
* 定義リスト記法
* 引用記法
* pre記法
* スーパーpre記法
* 表組み記法
* 続きを読む記法
* pタグ停止記法
* 脚注記法
* 改行記法
* http記法
* 自動リンク停止記法
* 下書き記法
* キーワード記法
* 目次記法

== Changelog ==

= 2.1.0 =
* はてな記法解析結果のキャッシュ機能を追加

= 2.0.4 =
* 互換性維持のため`wphn_render()`を追加

= 2.0.3 =
* 改行の扱いで「何もしない」以外を選んだ場合`wpautop`フィルタを無効化
* 無効日指定の引き継ぎが、全ての投稿を対象してしまう不具合を修正

= 2.0.2 =
* HTMLが全てエスケープされる不具合を修正

= 2.0.1 =
* 「リンクを別ウィンドウで開く」オプションの不具合を修正
* http記法のタイトル取得に失敗するとFatal errorが出る不具合を修正
* 「本文部分のHTML」オプションが機能しない不具合を修正

= 2.0 =
* WP Hatena Notation 2.0 Released!
