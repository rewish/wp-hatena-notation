<?php wp_nonce_field($this->domain, $this->nonceKey()); ?>

<input type="hidden" name="<?php echo $this->fieldName('Post.enabled'); ?>" value="0">
<input id="<?php echo $this->id('enabled'); ?>" type="checkbox" name="<?php echo $this->fieldName('Post.enabled'); ?>" value="1"<?php if ($enabled): ?> checked="checked"<?php endif; ?>>
<label for="<?php echo $this->id('enabled'); ?>">はてな記法を使用</label>