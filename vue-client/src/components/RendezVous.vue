<template>
  <div>
    {{dateDebut}} <img :src="require('../assets/date.png')" /> {{dateFin}} {{description}}
  </div>
</template>

<script>
import axios from 'axios'

export default {      
    props: ['dateDebut', 'dateFin', 'description'],
    data() {
        return { 
        }
    },
       
}

</script>