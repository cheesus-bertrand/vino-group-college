/* 
::config options::
  orientation: 'horizontal' (default) | 'vertical'
  size: 'sm' (32px) | 'md' (42px, default) | 'lg' (54px)
  showLabels: true (default) | false (hides text, forces square buttons)
  labelPosition: 'under' (default) | 'beside'
  variant: 'default' (light, default) | 'inverted' (dark/wine themed)
*/
<template>
  <div :class="['action-bar', orientation, `size-${size}`, `label-${labelPosition}`, variant, { 'hide-labels': !showLabels }]">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    props: {
      orientation: { type: String, default: 'horizontal' },
      size: { type: String, default: 'md' },
      showLabels: { type: Boolean, default: true },
      labelPosition: { type: String, default: 'under' },
      variant: { type: String, default: 'default' }
    }
  }
</script>

<style scoped>
  .action-bar {
    display: flex;
    background: var(--blanc-fonce);
    border: var(--espace-mini) solid var(--gris-clair);
    border-radius: 12px;
    overflow: hidden;
    box-sizing: border-box;
  }

  .inverted {
    background: var(--bordeau-profond);
    border-color: var(--rouge-framboise-fonce);
  }

  .horizontal { 
    flex-direction: row; 
    width: 100%; 
  }

  .horizontal.hide-labels {
    width: fit-content;
    margin-left: auto;
    margin-right: auto;
  }

  .horizontal.size-sm { height: 32px; }
  .horizontal.size-md { height: 42px; }
  .horizontal.size-lg { height: 54px; }

  .vertical { 
    flex-direction: column; 
    width: fit-content; 
    height: auto;
  }

  :deep(.action-item) {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--gris-fonce);
    text-decoration: none;
    transition: all 0.2s;
    gap: var(--espace-moyen-mini);
    white-space: nowrap;
    padding: 0 var(--espace-grand-mini);
    box-sizing: border-box;
    height: 100%;
  }

  .horizontal :deep(.action-item) { 
    flex: 1; 
  }

  .vertical :deep(.action-item) { 
    flex: none; 
    width: 100%;
  }

  .vertical.size-sm :deep(.action-item) { min-height: 40px; }
  .vertical.size-md :deep(.action-item) { min-height: 52px; }
  .vertical.size-lg :deep(.action-item) { min-height: 64px; }

  .inverted :deep(.action-item) { color: var(--blanc-fonce); }

  :deep(.action-item.is-active) {
    position: relative;
    z-index: 60;
    background: var(--rouge-framboise-fonce) !important;
    color: var(--blanc-fonce) !important;
  }
  .inverted :deep(.action-item.is-active) {
    background: var(--blanc-tres-clair) !important;
    color: var(--rouge-framboise-fonce) !important;
  }
  .label-under :deep(.action-item) { 
    flex-direction: column; 
    gap: var(--espace-tres-petit); 
    text-align: center;
    padding: var(--espace-moyen-mini);
  }

  .label-beside :deep(.action-item) { 
    flex-direction: row; 
    justify-content: center; 
  }
  
  .hide-labels :deep(span) { display: none; }

  .hide-labels :deep(.action-item) {
    padding: 0 !important;
    flex: none;
    width: 32px;
    aspect-ratio: 1 / 1;
  }

  .size-md.hide-labels :deep(.action-item) { width: 42px; }
  .size-lg.hide-labels :deep(.action-item) { width: 54px; }

  .horizontal :deep(.action-item:not(:last-child)) { border-right: var(--espace-mini) solid var(--gris-clair); }
  .vertical :deep(.action-item:not(:last-child)) { border-bottom: var(--espace-mini) solid var(--gris-clair); }

  .inverted.horizontal :deep(.action-item:not(:last-child)) { border-right-color: var(--rouge-framboise-fonce); }
  .inverted.vertical :deep(.action-item:not(:last-child)) { border-bottom-color: var(--rouge-framboise-fonce); }

  :deep(.action-item:hover) {
    background: var(--blanc-tres-clair);
    color: var(--rouge-framboise-fonce);
  }
  .inverted :deep(.action-item:hover) {
    background: var(--rouge-framboise-fonce);
    color: var(--blanc-fonce);
  }

  :deep(svg) { 
    display: block; 
    flex-shrink: 0; 
    margin: 0 !important; 
  }
  
  .size-sm :deep(svg) { width: 16px; height: 16px; }
  .size-md :deep(svg) { width: 20px; height: 20px; }
  .size-lg :deep(svg) { width: 24px; height: 24px; }

  :deep(span) {
    font-size: var(--fs-12);
    line-height: 1; 
    font-weight: 500;
    display: block;
    margin: 0;
  }
</style>