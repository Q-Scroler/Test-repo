<div id="stamp-customizer">
  {for $i=1 to $stamp_lines}
    <div class="form-group">
      <label for="stamp-line{$i}">{l s='Line'} {$i}</label>
      <input type="text" id="stamp-line{$i}" class="form-control stamp-line" />
    </div>
  {/for}
  <p><strong>{l s='Price:'} <span id="stamp-price">0</span></strong></p>
  <button type="button" id="stamp-submit" class="btn btn-primary" style="margin-top:10px;">{l s='Preview'}</button>
  <div id="stamp-preview" style="border: {$stamp_border}px solid {$stamp_color}; width: {$stamp_width}px; height: {$stamp_height}px; color: {$stamp_color}; border-radius: {$stamp_border_radius};"></div>
</div>
<script>
  var stamp_price_per_line = '{$stamp_price_per_line|escape:'javascript'}';
  var stamp_max_lines = {$stamp_lines|intval};
</script>
