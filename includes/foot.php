  <div id="site-footer"></div>

  <script nonce="<?php echo $cspNonce ?? ''; ?>" src="assets/js/shared.js"></script>
  <script nonce="<?php echo $cspNonce ?? ''; ?>"><?php echo preg_replace('/<\/script/i', '<\\/script', $inlineJS ?? ''); ?></script>
</body>
</html>
