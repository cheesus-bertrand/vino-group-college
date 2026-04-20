<template>
  <!-- Modal de confirmation composée de 3 éléments : 
   un message, un bouton de confirmation et un bouton d'annulation -->
  <div v-if="show">
    <div class="bloc-modale">
      <div class="bloc-modale-overlay" @click="$emit('cancel')"></div>

      <div class="bloc-modale-carte">
        <h2>{{ message }}</h2>

        <div class="bloc-modale-btn">
          <button class="signup-btn" @click="$emit('confirm')">
            {{ confirmText }}
          </button>

          <button class="signup-btn btn-annuler" @click="$emit('cancel')">
            {{ cancelText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ModalConfirmation",
  props: {
    // show permet d'afficher ou de masquer la modale de confirmation
    show: Boolean,
    // message est le message qui s'affiche dans la modale de confirmation
    message: {
      type: String,
      default: "Êtes-vous sûr ?",
    },
    //  texte qui s'affiche sur le bouton de confirmation
    confirmText: {
      type: String,
      default: "Confirmer",
    },
    // texte qui s'affiche sur le bouton d'annulation
    cancelText: {
      type: String,
      default: "Annuler",
    },
  },
};
</script>
