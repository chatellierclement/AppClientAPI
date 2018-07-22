<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg">
        <div id="table">        
          <table>                
            <div class="container-fluid parent" id="titre">
              <div class="row enfant">
                <input class="col-2 col-sm-2 col-lg-2 btn btn-default" type='button' v-on:click='precedent' value="<">
                <span class="col-8 col-sm-8 col-lg-8">{{ calendrier.calendrierMois }}</span>             
                <input class="col-2 col-sm-2 col-lg-2 btn btn-default" type='button' v-on:click='suivant' value=">"><br>
                <input class="col-lg-12 btn btn-default" type='button' v-on:click='aujourdhui' id='ajd' value="Aujourd'hui">  
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
      </div>
      <div class="col-lg">
        <div class="container-fluid">
          <div style="text-align:center" class="row" v-show="composentDroite">
           <!-- <div class="col-lg-12">
              <button type="button" v-on:click="afficheRdvForm" class="btn btn-primary">Ajouter un RDV</button>
            </div> -->       
            <div class="col-lg-12 alert alert-success" v-if="message != ''" v-text="message"></div>        
            <rendez-vous-form class="col-lg-12" v-show="rendezvousform" 
                              @completed="ajoutRDV" 
                              v-bind:dateEnCours="dateEnCours"
                              v-bind:periodes="periodes">
            </rendez-vous-form>  
            <div class="col-lg-12" v-show="rendezvous">
            <!--  <rendez-vous v-if="rdvs != ''"
                        @completed="delRDV"
                        v-for="rdv in rdvs"
                        v-bind:id="rdv.id"                   
                        v-bind:dateDebut="rdv.dateDebut"
                        v-bind:dateFin="rdv.dateFin"
                        v-bind:description="rdv.description">
              </rendez-vous> -->
              <test v-bind:rdvs="rdvs"></test>
              <div class="col-lg-12" v-if="rdvs == ''">Aucun rendez-vous prévu à cette date.</div>        
            </div>
          </div>
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
import Test from './Test.vue'

export default {
    components: {
        RendezVous,
        RendezVousForm,
        Test
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
            const path = "http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/calendrier/")
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
                const path = "http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/rendezvous/").concat(date);       
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
                const path = "http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/calendrier/").concat(compteur);                
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
                axios.post("http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/periode"), JSON.stringify(this.dateEnCours))
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
  }
  #table {
    text-align:center;  
    width:100%
  }
  table { 
    table-layout: auto ;   
    border-collapse: collapse;
    border-spacing: 0; 
    width:auto;
    margin: auto !important;
    font-size: 1em;
    -webkit-box-shadow: 5px 5px 5px #fff;
    box-shadow: 5px 5px 5px #fff;
    background: #fff;
    border-radius: 2px;
    display: inline-block;
    position: relative;
    -webkit-box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
    box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
  }
  td, th {
    padding: 0.75em;
  }
  td span {
    position: absolute;
    z-index: 2;
    background: red;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    font-size: 9px;
    color: #fff;
    line-height: 20px;
    white-space: nowrap;
  }  
  td {
    cursor: pointer;
  }
  td:hover {
    background:grey;
    color:white
  }
  .actif {
    background: green;
  }
  #ajd {      
    margin-top: 0.25em !important;
  }
</style>