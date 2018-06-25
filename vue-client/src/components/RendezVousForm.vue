<template>
    <form @submit.prevent="onSubmit">
        <span class="help is-danger" v-text="errors"></span>

        <div class="field">
            <div class="control">
                <input class="input" placeholder="Description" v-model="title" @keydown="errors = ''">
            </div>
        </div>

        <button class="button is-primary" >Enregistrer</button>
    </form>
</template>