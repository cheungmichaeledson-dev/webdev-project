
  import Chatbox from 'https://cdn.jsdelivr.net/npm/@chaindesk/embeds@latest/dist/chatbox/index.js';

  const widget = await Chatbox.initBubble({
    agentId: 'cmojwi1gn0mwyy9nevmd09etl',
    
    // optional 
    // If provided will create a contact for the user and link it to the conversation
    contact: {
      firstName: 'Bro',
      lastName: 'Bro',
      email: 'BRO@email.com',
      phoneNumber: '123456',
      userId: '42424242',
    },
    // optional
    // Override initial messages
    initialMessages: [
      'Hello IntramuBRO how are you doing today?',
      'How can I help you ?',
    ],
    // optional
    // Provided context will be appended to the Agent system prompt
    context: "The user you are talking to is Bro. Start by Greeting him by his name.",
  });

  // open the chat bubble
  widget.open();

  // close the chat bubble
  widget.close()

  // or 
  widget.toggle()
