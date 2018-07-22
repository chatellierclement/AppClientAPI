<template>
  <div>
    <div class="col-lg-12 alert alert-success" v-if="message != ''" v-text="message"></div>  
    <input type="hidden" v-model="id" />
    <vue-ckeditor v-model="content" :config="config" @blur="onBlur($event)" @focus="onFocus($event)"></vue-ckeditor>
    <button type="button" v-on:click="enregistrerModele" class="btn btn-primary">Enregistrer</button>
  </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'

export default {
  data() {
    return {
      id: '',
      content: '',
      message: '',
      config: {
        toolbar: [
          ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
        ],
        height: 300
      }
    };
  },
  async created () { 
       try {
            const path = "http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/modele");       
            const response = await axios.get(path)
            this.content = response.data["texte"]
            this.id = response.data["id"]
        } catch(e) {
            // handle authentication error here
        }       
  },
  methods: {
    onBlur(editor) {
      console.log(editor);
    },
    onFocus(editor) {
      console.log(editor);
    },
    async enregistrerModele() { 
        axios.post("http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/enregistrer"), JSON.stringify(this.$data))
                .then(response => {   
                  this.message = "Le modèle de courrier a été mis à jour."        
                })
                .catch(error => {
                })
        },
  }
};
</script>