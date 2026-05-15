
  import Chatbox from 'https://cdn.jsdelivr.net/npm/@chaindesk/embeds@latest/dist/chatbox/index.js';

  const widget = await Chatbox.initBubble({
    agentId: 'cmojwi1gn0mwyy9nevmd09etl',
    
    
    contact: {
      firstName: 'Bro',
      lastName: 'Bro',
      email: 'BRO@email.com',
      phoneNumber: '123456',
      userId: '42424242',
    },


    initialMessages: [
      'Hello IntramuBRO how are you doing today?',
      'How can I help you ?',
    ],


    context: "The user you are talking to is Bro. Start by Greeting him by his name.",
  });

  widget.open();

  widget.close()

  widget.toggle()

