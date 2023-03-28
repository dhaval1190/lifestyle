// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
  apiKey: "AIzaSyA31EsSr68dVVQ-cVZwfbLmeDK8_PUT2fM",
  authDomain: "coachhq-c1b3d.firebaseapp.com",
  projectId: "coachhq-c1b3d",
  storageBucket: "coachhq-c1b3d.appspot.com",
  messagingSenderId: "668525619724",
  appId: "1:668525619724:web:e4282225654d0467655c29",
  measurementId: "G-VFGKZNYRVM"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});