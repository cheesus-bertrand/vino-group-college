import { defineStore } from "pinia";

// store pour gerer les notifications sur d'autre pages
export const useNotifStore = defineStore("notif", {
  // on veut stocker le message et le type de message
  state: () => ({
    message: null,
    type: "bloc-modale-succes",
  }),
  actions: {
    // action qui aide a montrer un message/ notification sur une autre page
    montreMessage(message, type = "bloc-modale-succes") {
      this.message = message;
      this.type = type;
      setTimeout(() => {
        this.message = null;
      }, 3000);
    },
    //pour enlever le message/ notification sur l'autre page
    enleveMessage() {
      this.message = null;
    },
  },
});
