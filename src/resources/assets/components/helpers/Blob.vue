<template>
  <div>
    {{blob.full_name}}
  </div>
</template>

<script>
  import Blob from '../../models/Blob'

  export default {
    name: 'blob',

    props: {
      blob: {type: Blob}
    }
  }
</script>
