//for the communication window 
 function PopComunication(url) {
    
   newwindow = window.open(url,'Assistens','height=800,width=500');
   if ( newin .focus ()){newin .focus()}
    }


    //feth all the packages to use
    const app = require('express')();
    const server = require('http').Server(app);
    const io = require('socket.io')(server);

    // the port where is going to open used the pc port
    const port = 3000;

    server.listen(port, ()=>{
       console.log(`Server es listening on Port: ${port}`)
    });
    
    //if i put /abc, you have to put on the localhost:3000/abc
    app.get('/', (req, res)=>{
       //the corrent directury we are working on
       //sends the communication.html file
      res.sendFile(__dirname + '/public/communication.html');
    });
   app.get('/funcionario', (req, res)=>{
      res.sendFile(__dirname + '/public/funcionario.html');
   });

   const tech = io.of('/tech');

   //sockets chat io.socket, all the time that the page is refreshed it connects
   tech.on('connection', (socket)=>{
      //cheek the data, then emit the ms
      socket.on('join',(data)=>{
         socket.join(data.room);
         tech.in(data.room).emit('message', `New User Joined ${data.room} root`);
      });

      socket.on('message', (data)=>{
         tech.in(data.room).emit('message',data.ms);
      });

      io.on('disconnect',()=>{
         console.log('User disconnected');
         tech.emit('message','User disconnected');
      });

   });


