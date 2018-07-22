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
                    <td tabindex="0" v-if="jour[0] != '*'" :class="jour[1]" :id="jour[3]" v-on:click='recupererDate(jour[3])'>{{jour[0]}}
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
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Vue from 'vue'

export default {
    components: {
    },
    data() {
        return {
            compteur: 0,
            calendrier: {},
        }
    },
    created () {        
      this.calculCalendrier(this.compteur)
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
        async calculCalendrier (compteur) { 
            try {
                const path = "http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/calendrier/").concat(compteur);                
                const response = await axios.get(path)
                this.calendrier = response.data
            } catch(e) {
                // handle authentication error here
            }            
           
        },
        recupererDate (date) {            
            this.$emit('date', date)
        },
    }
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