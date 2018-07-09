<template>
   <div> 
      <div id="calendrier" class="col-md-6">         
        <table>                
        <div id="titre">
            <div>
              <input class="btn btn-default" type='button' v-on:click='precedent' value="<">
              <span>{{ calendrier.calendrierMois }}</span>             
              <input class="btn btn-default" type='button' v-on:click='suivant' value=">"><br>
              <input class="col-md-10 btn btn-default" type='button' v-on:click='aujourdhui' id='ajd' value="Aujourd'hui">  
            </div>  
        </div>
            <thead>
                <tr>
                    <th>L</th>
                    <th>M</th>
                    <th>M</th>
                    <th>J</th>
                    <th>V</th>
                    <th>S</th>
                    <th>D</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="semaine in calendrier.calendrier">
                  <tr>
                <template v-for="jour in semaine">                  
                  <td tabindex="0" v-if="jour[0] != '*'" :class="jour[1]" :id="jour[3]" v-on:click='rendezvousAffiche(jour[3])'>{{jour[0]}}
                    <span v-if="jour[2] != 0">{{jour[2]}}</span>
                  </td>
                  <td v-else></td>
                </template>
                  </tr>         
                </template>  
            </tbody>
        </table>
    </div>
    <div v-show="composentDroite" id="rdv" class="col-md-6">
      <button type="button" v-on:click="afficheRdvForm" class="btn btn-primary">Ajouter un RDV</button>
      <div>        
        <div class="alert alert-success" v-if="message != ''" v-text="message"></div> 
        
        <rendez-vous-form v-show="rendezvousform" 
                          @completed="ajoutRDV" 
                          v-bind:dateEnCours="dateEnCours"
                          v-bind:periodes="periodes">
        </rendez-vous-form>  
        
        <div v-show="rendezvous">
          <rendez-vous v-if="rdvs != ''"
                @completed="delRDV"
                 v-for="rdv in rdvs"
                 v-bind:id="rdv.id"                   
                 v-bind:dateDebut="rdv.dateDebut"
                 v-bind:dateFin="rdv.dateFin"
                 v-bind:description="rdv.description">
          </rendez-vous>
          <div v-if="rdvs == ''">Aucun rendez-vous prévu à cette date.</div>        
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import RendezVous from './RendezVous.vue'
import RendezVousForm from './RendezVousForm.vue'

export default {
    components: {
        RendezVous,
        RendezVousForm
    },
    data() {
        return {
            composentDroite : false,            
            rendezvousform : false,
            rendezvous: false,
            compteur: 0,
            calendrier: {},
            rdvs: {},
            message: '',
            dateEnCours: '',
            periodes: ''
        }
    },
    async created () {        
        try {
            const path = "http://localhost/symfonyVueJS_v2/public/index.php".concat(this.$route.fullPath);       
            const response = await axios.get(path)
            this.calendrier = response.data
        } catch(e) {
            // handle authentication error here
        }
    },
    methods: {        
        suivant () {    
           this.composentDroite = false   
           this.calculCalendrier(++this.compteur)
        },
        precedent () {  
           this.composentDroite = false       
           this.calculCalendrier(--this.compteur)
        },
        aujourdhui () { 
           this.composentDroite = false         
           this.compteur = 0;
           this.calculCalendrier(this.compteur)
        },
        async rendezvousAffiche (date) {            
            try {
                const path = "http://localhost/symfonyVueJS_v2/public/index.php/rendezvous/".concat(date);       
                const response = await axios.get(path)
                this.rdvs = response.data                
            } catch(e) {
                // handle authentication error here
            }   
            this.composentDroite = true  
            this.rendezvous = true   
            this.rendezvousform = false  
            this.dateEnCours = date 
            this.message = ''  
        },
        async calculCalendrier (compteur) { 
            try {
                const path = "http://localhost/symfonyVueJS_v2/public/index.php".concat(this.$route.fullPath).concat("/").concat(compteur);                
                const response = await axios.get(path)
                this.calendrier = response.data
            } catch(e) {
                // handle authentication error here
            }            
           
        },
        delRDV(reponse) {
            this.changementRdv(reponse)
        },
        ajoutRDV(reponse) {
            this.changementRdv(reponse)
        },
        async afficheRdvForm() {
            try {
                axios.post('http://localhost/symfonyVueJS_v2/public/index.php/periode', JSON.stringify(this.dateEnCours))
                    .then(response => {                   
                        this.periodes = response.data
                        this.rendezvousform = true
                        this.rendezvous = false
                        this.composentDroite = true
                        this.message = ''
                    })
                    .catch(error => {
                    })     
            } catch(e) {
            }           
        },
        changementRdv(reponse) {          
            this.rdvs = reponse[0]
            this.composentDroite = true            
            this.rendezvous = true
            this.rendezvousform = false
            this.message = reponse[1]
            this.calculCalendrier(this.compteur)
        }
    },   
}

</script>

<style scoped>

  #titre {
    background: red;
    height: 10em; 
    color:white; 
    position: relative;
  }


  #calendrier {
    text-align:center;
    float:left;
    margin-top: 10vh; 
  }

  #rdv {
    float:right;
     text-align:justify;
    margin-top: 10vh;
  }

  table {
    margin:auto;
    font-size:1em;
    box-shadow: 5px 5px 5px white;
    background: #fff;
    border-radius: 2px;
    display: inline-block;
    margin: 1rem;
    position: relative;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}


  th, td {
    padding:1em;
    white-space:nowrap;
  }

  td {
    cursor: pointer;
  }

  td:hover {
    background:grey;
    color:white
  }

  td span {
    position:absolute;
    z-index:2;
    background: red;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    font-size:9px;
    color:white;
    line-height: 20px; 
    white-space: nowrap;
    margin-top: -10px 
   }

   ul, li {
      padding:0
   }

   li {
    display: inline-block;
    list-style-type: none;
   }

   .actif {
      background: green;
   }

   #rdv button {
      margin-bottom: 3em
   }

   #titre input {      
      margin: 0 0.5em
   }

   #ajd {      
      margin-top: 1em !important;
   }

   #titre div {      
      position: absolute;
      top: 20%; 
      transform: translateY(-20%);
      left: 50%; 
      transform: translateX(-50%);
   }

</style>