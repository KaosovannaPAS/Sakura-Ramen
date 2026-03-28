const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const mongoose = require('mongoose');
const cors = require('cors');
require('dotenv').config({ path: '../../.env' });

const app = express();
app.use(cors());
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"]
  }
});

// MongoDB Connection
const mongoUri = process.env.MONGO_URI || 'mongodb://localhost:27017/sakura_logs';
mongoose.connect(mongoUri)
  .then(() => console.log('Connected to MongoDB'))
  .catch(err => console.error('MongoDB connection error:', err));

// Schemas
const EventSchema = new mongoose.Schema({
  type: String,
  message: String,
  data: Object,
  timestamp: { type: Date, default: Date.now }
});
const Event = mongoose.model('Event', EventSchema);

const StatusSchema = new mongoose.Schema({
  isOpen: Boolean,
  currentService: String, // 'midi', 'soir', 'fermé'
  queueLength: Number,
  lastUpdated: { type: Date, default: Date.now }
});
const Status = mongoose.model('Status', StatusSchema);

// Initial Status
let restaurantStatus = {
  isOpen: false,
  currentService: 'fermé',
  queueLength: 0
};

// Real-time Logic
io.on('connection', (socket) => {
  console.log('A user connected:', socket.id);
  
  // Send current status on connect
  socket.emit('statusUpdate', restaurantStatus);

  // Handle Admin updates
  socket.on('adminUpdateStatus', async (data) => {
    restaurantStatus = { ...restaurantStatus, ...data };
    io.emit('statusUpdate', restaurantStatus);
    
    // Log to MongoDB
    await Status.create(restaurantStatus);
    await Event.create({ type: 'STATUS_CHANGE', message: 'Restaurant status updated', data });
  });

  socket.on('disconnect', () => {
    console.log('User disconnected');
  });
});

// Simulation: Dynamic Traffic Update every 30s
setInterval(() => {
  if (restaurantStatus.isOpen) {
    restaurantStatus.queueLength = Math.floor(Math.random() * 15);
    io.emit('statusUpdate', restaurantStatus);
  }
}, 30000);

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Real-time server running on port ${PORT}`);
});
