let connected = false;
const socket = io("http://localhost:3000", {
  cors: {
    origin: "http://localhost:80",
    methods: ["GET", "POST"],
    credentials: true,
  },
});
socket.on('connect', function () {
  console.log(socket)
  if (!connected) {
    console.log('Conectado al servidor');
    socket.emit('join', 'guibdental');
    socket.emit('getNotify', 'guibdental,0');
    socket.on('notifications', function (data) {
      console.log('datos de notificacion ', data)
      document.getElementById('cantidad').innerHTML(data.cantidad)
    })
    socket.on('message', function (data) {
      console.log('MENSAJE: ', data)
    })
    connected = true;
  }
});

socket.on('disconnect', function () {
  console.log('Desconectado del servidor');
  alert('desconectado')
  connected = false;
})