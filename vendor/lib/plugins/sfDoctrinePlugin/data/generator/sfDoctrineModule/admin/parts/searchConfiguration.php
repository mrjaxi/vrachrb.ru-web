  public function hasSearch()
  {
    return <?php echo (isset($this->config['list']['search']['fields']) && is_array($this->config['list']['search']['fields']) && count($this->config['list']['search']['fields']) > 0 ? 'true' : 'false') ?>;
  }
  public function getSearchFields()
  {
    return '<?php echo (isset($this->config['list']['search']['fields']) && is_array($this->config['list']['search']['fields']) && count($this->config['list']['search']['fields']) > 0 ? 'TRIM(' . implode('), TRIM(', $this->config['list']['search']['fields']) . ')' : 'false') ?>';
<?php unset($this->config['list']['search']) ?>
  }
