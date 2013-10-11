<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php echo self::PAGE_TITLE ?></h2>

	<form method="post" action="options.php">
		<div class="hiddens">
			<?php wp_nonce_field('update-options'); ?>
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="page_options" value="<?php echo $this->domain; ?>">
		</div>

		<h3>レンダリング</h3>
		<table class="form-table">
			<tbody>
				<tr>
					<th>パフォーマンス</th>
					<td>
						<label>
							<input type="hidden" name="<?php echo $this->fieldName('Renderer.cache'); ?>" value="0">
							<input type="checkbox" name="<?php echo $this->fieldName('Renderer.cache'); ?>" value="1"<?php if ($options->Renderer->cache): ?> checked="checked"<?php endif; ?>>
							記事のレンダリング結果をキャッシュ
						</label>
					</td>
				</tr>

				<tr>
					<th>見出し記法基準レベル</th>
					<td>
						<select name="<?php echo $this->fieldName('Renderer.headerlevel'); ?>">
						<?php for ($i = 1; $i <= 6; $i++): ?>
							<option value="<?php echo $i; ?>"<?php if ((int)$options->Renderer->headerlevel === $i) echo ' selected="selected"'; ?>>h<?php echo $i; ?></option>
						<?php endfor; ?>
						</select>
					</td>
				</tr>

				<tr>
					<th>リンクを別ウィンドウで開く</th>
					<td>
						<input id="wp_hatena_notation_renderer_link_target_blank_true" type="radio" name="<?php echo $this->fieldName('Renderer.link_target_blank'); ?>" value="1"<?php if ($options->Renderer->link_target_blank): ?> checked="checked"<?php endif; ?>>
						<label for="wp_hatena_notation_renderer_link_target_blank_true">はい</label>
						<input id="wp_hatena_notation_renderer_link_target_blank_false" type="radio" name="<?php echo $this->fieldName('Renderer.link_target_blank'); ?>" value="0"<?php if (!$options->Renderer->link_target_blank): ?> checked="checked"<?php endif; ?>>
						<label for="wp_hatena_notation_renderer_link_target_blank_false">いいえ</label>
						<p class="description">http記法に<code>target="_blank"</code>を追加します。</p>
					</td>
				</tr>

				<tr>
					<th>http記法のタイトル再取得</th>
					<td>
						<input type="text" name="<?php echo $this->fieldName('Renderer.title_expires'); ?>" value="<?php echo $options->Renderer->title_expires; ?>" style="width:3em;text-align:center;">日後に再取得
						<p class="description">未入力にすると再取得が無効になります。</p>
					</td>
				</tr>

				<tr>
					<th>改行の扱い</th>
					<td>
						<select name="<?php echo $this->fieldName('Renderer.linebreak_method'); ?>">
							<option value="wpautop"<?php if ($options->Renderer->linebreak_method === 'wpautop'): ?> selected="selected"<?php endif; ?>>wpautop関数を適用（推奨）</option>
							<option value="wordpress"<?php if ($options->Renderer->linebreak_method === 'wordpress'): ?> selected="selected"<?php endif; ?>>何もしない（WordPressの改行ルールに従う）</option>
							<option value="plugin"<?php if ($options->Renderer->linebreak_method === 'plugin'): ?> selected="selected"<?php endif; ?>>改行を全てpとして扱う（廃止予定）</option>
						</select>
						<p class="description"><code>&lt;p&gt;</code>や<code>&lt;br&gt;</code>を挿入する方法を指定します。</p>
					</td>
				</tr>

				<tr>
					<th>本文部分のHTML</th>
					<td>
						<textarea style="width: 30em;height:6em;" name="<?php echo $this->fieldName('Renderer.textbody_html'); ?>"><?php echo esc_html($options->Renderer->textbody_html); ?></textarea>
						<p class="description"><code>%content%</code>の部分に本文が挿入されます。</p>
					</td>
				</tr>

				<tr>
					<th>脚注部分のHTML</th>
					<td>
						<textarea style="width: 30em;height:6em;" name="<?php echo $this->fieldName('Renderer.footnote_html'); ?>"><?php echo esc_html($options->Renderer->footnote_html); ?></textarea>
						<p class="description"><code>%content%</code>の部分に脚注が挿入されます。</p>
					</td>
				</tr>

				<tr>
					<th style="padding-bottom:2px;" rowspan="2">スーパーpre記法の色分け方法</th>
					<td style="padding-bottom:2px;">
						<select id="wp_hatena_notation_superpre_method" name="<?php echo $this->fieldName('Renderer.superpre_method'); ?>">
							<option value="geshi"<?php if ($options->Renderer->superpre_method === 'geshi'): ?> selected="selected"<?php endif; ?>>Built-In Highlighterで色分け（GeSHi）</option>
							<option value="html"<?php if ($options->Renderer->superpre_method === 'html'): ?> selected="selected"<?php endif; ?>>JSなどで独自に色分け（HTML）</option>
						</select>
					</td>
				</tr>

				<tr id="wp_hatena_notation_superpre_geshi">
					<td class="description">色分けの定義はCSSでおこないます。<code><?php echo esc_html('<link rel="stylesheet" href="' . $highlightCSS . '">'); ?></code>を<code>head</code>タグの中に追加するか、<a href="<?php echo $highlightCSS; ?>" target="_blank">highlight.css</a>を参考にCSSを作成してください。</td>
				</tr>

				<tr id="wp_hatena_notation_superpre_html">
					<td>
						<textarea style="width: 30em;height:6em;" name="<?php echo $this->fieldName('Renderer.superpre_html'); ?>"><?php echo esc_html($options->Renderer->superpre_html); ?></textarea>
						<p class="description"><code>%type%</code>に言語の種類、<code>%content%</code>にコードが挿入されます。</p>
					</td>
				</tr>
			</tbody>
		</table>

		<h3>個別設定</h3>
		<table class="form-table">
			<tr>
				<th rowspan="2">記事ごとの設定</th>
				<td>
					<input type="hidden" name="<?php echo $this->fieldName('PostSetting.per_post'); ?>" value="0">
					<input id="wp_hatena_notation_per_post" type="checkbox" name="<?php echo $this->fieldName('PostSetting.per_post'); ?>" value="1"<?php if ($options->PostSetting->per_post): ?> checked="checked"<?php endif; ?>>
					<label for="wp_hatena_notation_per_post">使用/不使用の切り替えを許可する</label>
					<div id="wp_hatena_notation_per_post_default_wrap">
						┗<input type="hidden" name="<?php echo $this->fieldName('PostSetting.per_post_default'); ?>" value="0">
						<input id="wp_hatena_notation_per_post_default" type="checkbox" name="<?php echo $this->fieldName('PostSetting.per_post_default'); ?>" value="1"<?php if ($options->PostSetting->per_post_default): ?> checked="checked"<?php endif; ?>>
						<label for="wp_hatena_notation_per_post_default">初期状態で「はてな記法を使用」にチェックを入れる</label>
					</div>
				</td>
			</tr>
		</table>

		<p class="submit"><input type="submit" class="button-primary" value="保存"></p>
	</form>


	<h3>サポート情報</h3>
	<p>不具合報告やご要望は<a href="https://github.com/rewish/wp-hatena-notation" target="_blank">GitHub</a>の<a href="https://github.com/rewish/wp-hatena-notation/issues" target="_blank">Issues</a>からいただければ幸いです。Pull Request大歓迎！！</p>
</div>

<script src="<?php echo $pageJS; ?>"></script>
