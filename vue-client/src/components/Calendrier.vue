<template>
   <div class="col-md-10"> 
      <div id="calendrier">         
        <table> 
        <div id="titre">{{ calendrier.calendrierMois }}</div>        
        <button v-on:click='suivant' id='logout-button'> Suiv </button>
        <button v-on:click='precedent' id='logout-button'> Prec </button>
        <button v-on:click='aujourdhui' id='logout-button'> Today </button>
     
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
                  <td v-if="jour[0] != '*'" :class="jour[1]" :id="jour[3]" v-on:click='rendezvousAffiche(jour[3])'>{{jour[0]}}
                    <span v-if="jour[2] != 0">{{jour[2]}}</span>
                  </td>
                  <td v-else></td>
                </template>
                  </tr>         
                </template>  
            </tbody>
        </table>
    </div>
    <div>
      <rendez-vous v-show="rendezvous"
                   v-for="rdv in rdvs"
                   v-bind:dateDebut="rdv.dateDebut"
                   v-bind:dateFin="rdv.dateFin"
                   v-bind:description="rdv.description"></rendez-vous>
      <div v-show="rendezvous" v-if="rdvs==''">Pas de rendez-vous prévu à cette date</div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'
import RendezVous from './RendezVous.vue'

export default {
    components: {
        RendezVous
    },
    data() {
        return {
            rendezvous : false,
            compteur: 0,
            calendrier: {},
            rdvs: {}
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
           this.calculCalendrier(++this.compteur)
        },
        precedent () {          
           this.calculCalendrier(--this.compteur)
        },
        aujourdhui () {          
            this.compteur = 0;
           this.calculCalendrier(this.compteur)
        },
        async rendezvousAffiche (date) {
            this.rendezvous = true   
            try {
                const path = "http://localhost/symfonyVueJS_v2/public/index.php/rendezvous/".concat(date);       
                const response = await axios.get(path)
                this.rdvs = response.data
            } catch(e) {
                // handle authentication error here
            }     
        },
        async calculCalendrier (compteur) {
           this.rendezvous = false  
            try {
                const path = "http://localhost/symfonyVueJS_v2/public/index.php".concat(this.$route.fullPath).concat("/").concat(compteur);                
                const response = await axios.get(path)
                this.calendrier = response.data
            } catch(e) {
                // handle authentication error here
            }
        }
    },   
}

</script>

<style scoped>

  #titre {
    background: red;
    height: 10em; 
    line-height: 10em; 
    white-space: nowrap;
    color:white; 
  }


  #calendrier {
    text-align:center
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

  span {
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
</style>