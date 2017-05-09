<template>
  <div>
    <create-folder-btn></create-folder-btn>
    <change-view-btn></change-view-btn>
    <rename-btn></rename-btn>
    <delete-btn></delete-btn>
  </div>
</template>

<script>
  import createFolderBtn from './CreateFolderBtn.vue'
  import renameBtn from './RenameBtn.vue'
  import deleteBtn from './DeleteBtn.vue'
  import changeViewBtn from './ChangeViewBtn.vue'

  export default {
    name: 'actions-bar',

    components: {
      createFolderBtn,
      renameBtn,
      deleteBtn,
      changeViewBtn
    }
  }
</script>
