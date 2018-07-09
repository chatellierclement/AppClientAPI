<template>
    <div id="tt">
        <form> 
            <div>Choisir une des périodes disponibles</div>
            <div id="periodes" v-if="periodes != ''" >
                <template v-for="(periode, index) in periodes"> 
                        <input v-on:change="periodeChange(index)" :checked='false' type="radio" name="group"> {{periode.plageDispoDebut}} - {{periode.plageDispoFin}}<br>
                </template>
            </div>
            <div v-show="formulaire">
                <div id = "slider-6"></div>              
                <input id="slidevalue" style = "text-align:center;border:0; color:#b9cd6d; font-weight:bold;">           
                <input id = "changevalue" style = "text-align:center;border:0; color:#b9cd6d; font-weight:bold;">       
                <input class="input col-md-12" title="description" placeholder="Description" v-model.lazy="description"><br>           
                <button type="button" v-on:click="add(dateEnCours)" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios'

export default {   
    props: ['dateEnCours', 'periodes'],   
    data() {
        return { 
        	description: '',
            dateDebut: '',
            dateFin: '',
            dateEnCoursForm: '',
        	retour: '',
            max: 0,
            formulaire: false
        }
    },
    methods: {
        async add(dateEnCours) { 
            this.dateDebut = $( "#slidevalue" ).val()
            this.dateFin = $( "#changevalue" ).val()
            this.dateEnCoursForm = dateEnCours
            axios.post('http://localhost/symfonyVueJS_v2/public/index.php/ajouter', JSON.stringify(this.$data))
                .then(response => {
					this.retour = "Le rendez-vous a bien été enregistré"
                    this.$emit('completed', [response.data, this.retour]) 
                    this.formulaire = false                   
                })
                .catch(error => {
                })
        },
        periodeChange (index) {
            $( "#slidevalue" ).val(this.periodes[index].plageDispoDebut)
            $( "#changevalue" ).val(this.periodes[index].plageDispoFin)
            this.max = this.periodes[index].interval            
            var tempDate = this.dateEnCours.concat(" ").concat(this.periodes[index].plageDispoDebut).concat(":00")
            slider(this.max, tempDate)
            this.formulaire = true
        }
    }
       
}

function slider(max, dateEnCoursForm) {
    $( "#slider-6" ).slider({
       range:true,
       min: 0,
       max: max,
       values: [ 0, max ],               
       slide: function( event, ui ) {  
            var time = new Date(dateEnCoursForm).getTime();
            $( "#slidevalue" ).val( calculDate(time,ui.values[ 0 ]) );
            $( "#changevalue" ).val( calculDate(time,ui.values[ 1 ]) );
       },
   });
}

function calculDate(time,value) {
    var dt = new Date(time + value*60000);            
    var heure = dt.getHours().toString();
    if (heure < 10) { heure = "0".concat(heure); }
    var minute = dt.getMinutes().toString();
    if (minute < 10) { minute = "0".concat(minute); }    
    return heure.concat(":").concat(minute);
}
</script>

<style>
    #tt {
        text-align:center;
    }

    input {
        margin:0.75em 0;
        padding:0.2em 0 
    }

    #periodes {
        padding: 3em 0
    }
</style>
