<template>
<div id="template">
    <div id="rendezvouscontainer" class="derriere container-fluid">
      <div class="row">
        <template v-for="pp in rdvs.periode">
          <div class="col-md-6">       
            <template v-for="p in pp">
              <div class="marge col-md-12">
                  <div class="container-fluid">
                    <div class="row">
                        <div class="parent col-2 col-md-2"><span class="enfant">{{p}}h</span></div>
                        <div class="border col-10 col-md-01"> </div>           
                    </div>
                  </div>
              </div>
            </template>
          </div>
        </template>
      </div>
    </div>

    <div class="devant container-fluid">
      <div class="row">
       <template v-for="rrr in rdvs.rendezvous">
        <template v-for="rr in rrr">
          <div class="col-md-6">
            <template v-for="rdv in rr">
              <div class="col-md-12">
                <div class="container-fluid">
                  <div class="row">
                    <div class="parent col-2 col-md-2"><span class="enfant"></span></div>                  
                    <div v-if="rdv.reserver" style="padding:0" class="reserver col-10 col-md-10" v-bind:class = "rdv.couleur">
                      <div v-bind:style = "{ height:rdv.interval }" >
                        <span>{{rdv.description}}</span>
                      </div>
                    </div>
                    <div v-else v-bind:style = "{ height:rdv.interval }" class="noreserver col-11 col-md-11"></div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>
        </template>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios'

export default {      
    props: ['date'],
    data() {
        return { 
          rdvs: {}
        }
    },
    created () {
      this.rendezvousAffiche ()        
    },
    watch: {
       'date': function() {
          this.rendezvousAffiche ()
        }
    },
    methods: {        
        async rendezvousAffiche (date) {   
            axios.post("http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/rendezvous"), JSON.stringify(this.date))
                .then(response => {   
                  this.rdvs = response.data  
                })
                .catch(error => {
                })              
        },
    },        
}

</script>

<style>
  #template {
    position:relative;

  }
  #rendezvouscontainer {   
    -webkit-box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
    box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
    background: #fff;

  }
  .border {
    border:0.25px dashed grey !important;
    opacity:0.75;
    margin:2em 0;  
  }
  .marge {
    height:7em;
  }
  .parent {
    display: flex;
    padding-left:0;
    padding-right: 0
  }
  .enfant {
    margin:auto;
  }
  .derriere { 
    position: absolute;       
    z-index: 1;
    left:50%;
    transform: translateX(-50%);
  }
  .devant {
    position: absolute;
    z-index:2;
    margin-top: 2.0em;        
    left:50%;
    transform: translateX(-50%);
  }
  .reserver {
    position:relative;    
    z-index: 1;
  }
  .reserver:before {    
    border-radius:10px;
    content: "";
    position: absolute;
    z-index:-0;
    top:0;
    bottom:0;
    left:0;
    right:0;
    opacity:0.2
  }
  .couleur1:before {    
    background:green;
  }
  .couleur2:before {    
    background:red;
  }
  .reserver span {
    position: relative;
    z-index:10;   
  }
  .noreserver {
    background:none;
  }
</style>