<template>
    <div id="formulaire">
        <div class="conteneur-img">
            <img class="image-formulaire" src="../assets/img/Login_Image.png" alt="Image titre d'une cave a vins">
        </div>
        <form class="form" @submit.prevent="handleLogin">
            <h1>Connectez Vous</h1>
            <div class="form">
                <label class="gauche" for="email">Courriel</label>
                <input v-model="email" id="email" type="email" placeholder="Courriel" required>
            </div>
            <div class="form">
                <label class="gauche" for="password">Mot de passe</label>
                <input v-model="password" id="password" type="password" placeholder="Mot de passe" required>
            </div>
            <button class="btn-soumettre" type="submit" :disabled="loading">
                {{ loading ? 'Connexion...' : 'Se Connecter' }}
            </button>
            <button class="btn-second">
                {{ "S'inscrire" }}
            </button>
            <p v-if="error" class="error">{{ error }}</p>
        </form>
    </div>
</template>

<script setup>
  import { ref } from 'vue';
  import api, { fetchCsrfToken } from '../api';

  const email = ref('');
  const password = ref('');
  const error = ref('');
  const loading = ref(false);

  async function handleLogin() {
    loading.value = true;
    error.value = '';

    try {

      await fetchCsrfToken();

      const response = await api.post('/login', {
        email: email.value,
        password: password.value
      });

    } catch (err) {

      if (err.response) {
        error.value = err.response.data.message || 'Erreur de connexion';
      } else if (err.request) {
        error.value = 'Impossible de joindre le serveur';
      } else {
        error.value = 'Une erreur est survenue';
      }
    } finally {
      loading.value = false;
    }
  }
</script>