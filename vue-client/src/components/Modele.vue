<template>
  <div>
    <vue-ckeditor v-model="content" :config="config" @blur="onBlur($event)" @focus="onFocus($event)"></vue-ckeditor>
    <button type="button" v-on:click="enregistrerModele" class="btn btn-primary">Enregistrer</button>
  </div>
</template>

<script>
import Vue from 'vue'

export default {
  data() {
    return {
      content: '',
      config: {
        toolbar: [
          ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
        ],
        height: 300
      }
    };
  },
  created () {        
        this.content = "test"
  },
  methods: {
    onBlur(editor) {
      console.log(editor);
    },
    onFocus(editor) {
      console.log(editor);
    },
    async enregistrerModele() { 
        axios.post('http://localhost/symfonyVueJS_v2/public/index.php/enregistrer', JSON.stringify(this.content))
                .then(response => {                 
                })
                .catch(error => {
                })
        },
  }
};
</script>