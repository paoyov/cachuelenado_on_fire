/**
 * Chatbot para Cachueleando On Fire
 * Sistema de respuestas inteligentes para asesorar a clientes
 */

class CachueleandoChatbot {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.init();
    }

    init() {
        this.createChatbotHTML();
        this.attachEventListeners();
        this.addWelcomeMessage();
    }

    createChatbotHTML() {
        const chatbotHTML = `
            <div id="chatbot-container" class="chatbot-container">
                <div id="chatbot-header" class="chatbot-header">
                    <div class="chatbot-header-content">
                        <div class="chatbot-avatar">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="chatbot-info">
                            <h4>Cachueleando On Fire</h4>
                            <span class="chatbot-status">En línea</span>
                        </div>
                    </div>
                    <button id="chatbot-minimize" class="chatbot-minimize">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div id="chatbot-messages" class="chatbot-messages"></div>
                <div class="chatbot-quick-actions">
                    <button class="quick-action-btn" data-action="precios">💰 Precios</button>
                    <button class="quick-action-btn" data-action="servicios">🔧 Servicios</button>
                    <button class="quick-action-btn" data-action="registro">📝 Registro</button>
                </div>
                <div class="chatbot-input-container">
                    <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Escribe tu pregunta aquí..." />
                    <button id="chatbot-send" class="chatbot-send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
            <button id="chatbot-toggle" class="chatbot-toggle">
                <i class="fas fa-comments"></i>
                <span class="chatbot-badge">1</span>
            </button>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    attachEventListeners() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const minimizeBtn = document.getElementById('chatbot-minimize');
        const sendBtn = document.getElementById('chatbot-send');
        const input = document.getElementById('chatbot-input');
        const quickActions = document.querySelectorAll('.quick-action-btn');

        toggleBtn.addEventListener('click', () => this.toggleChatbot());
        minimizeBtn.addEventListener('click', () => this.toggleChatbot());
        sendBtn.addEventListener('click', () => this.handleSendMessage());
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.handleSendMessage();
            }
        });

        quickActions.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.target.getAttribute('data-action');
                this.handleQuickAction(action);
            });
        });
    }

    toggleChatbot() {
        const container = document.getElementById('chatbot-container');
        const toggleBtn = document.getElementById('chatbot-toggle');
        const badge = document.querySelector('.chatbot-badge');
        
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            container.classList.add('chatbot-open');
            toggleBtn.style.display = 'none';
            if (badge) badge.style.display = 'none';
            setTimeout(() => {
                document.getElementById('chatbot-input').focus();
            }, 300);
        } else {
            container.classList.remove('chatbot-open');
            toggleBtn.style.display = 'flex';
        }
    }

    addWelcomeMessage() {
        const welcomeMessage = {
            text: "¡Hola! 👋 Soy el asistente de Cachueleando On Fire. Estoy aquí para ayudarte a encontrar el maestro de oficio que necesitas. Puedes preguntarme sobre precios, servicios disponibles, cómo registrarte, o cualquier otra duda. ¿En qué puedo ayudarte?",
            sender: 'bot',
            timestamp: new Date()
        };
        this.addMessage(welcomeMessage);
    }

    handleQuickAction(action) {
        let message = '';
        switch(action) {
            case 'precios':
                message = '¿Cuánto cuesta contratar un maestro?';
                break;
            case 'servicios':
                message = '¿Qué servicios ofrecen?';
                break;
            case 'registro':
                message = '¿Cómo me registro?';
                break;
        }
        if (message) {
            this.addUserMessage(message);
            setTimeout(() => {
                this.processMessage(message);
            }, 500);
        }
    }

    handleSendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        
        if (message) {
            this.addUserMessage(message);
            input.value = '';
            setTimeout(() => {
                this.processMessage(message);
            }, 500);
        }
    }

    addUserMessage(text) {
        const message = {
            text: text,
            sender: 'user',
            timestamp: new Date()
        };
        this.addMessage(message);
    }

    addMessage(message) {
        this.messages.push(message);
        this.renderMessage(message);
    }

    renderMessage(message) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message chatbot-message-${message.sender}`;
        
        const time = this.formatTime(message.timestamp);
        
        messageDiv.innerHTML = `
            <div class="chatbot-message-content">
                ${message.sender === 'bot' ? '<div class="chatbot-avatar-small"><i class="fas fa-fire"></i></div>' : ''}
                <div class="chatbot-message-bubble">
                    <p>${this.escapeHtml(message.text)}</p>
                    <span class="chatbot-message-time">${time}</span>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    processMessage(userMessage) {
        const response = this.generateResponse(userMessage.toLowerCase());
        const botMessage = {
            text: response,
            sender: 'bot',
            timestamp: new Date()
        };
        
        // Simular delay de escritura
        setTimeout(() => {
            this.addMessage(botMessage);
        }, 300);
    }

    generateResponse(message) {
        // Respuestas basadas en palabras clave
        const responses = {
            // Precios
            precio: "Los precios varían según el tipo de trabajo y el maestro que elijas. Cada maestro establece sus propias tarifas basadas en su experiencia y el tipo de servicio. Te recomendamos buscar maestros en nuestra plataforma y contactarlos directamente para obtener un presupuesto personalizado. 💰",
            
            costo: "Los costos dependen del servicio que necesites. Por ejemplo: gasfitería básica puede costar desde S/ 50, electricidad desde S/ 80, albañilería desde S/ 100. Estos son precios aproximados y pueden variar. Lo mejor es contactar directamente con el maestro para un presupuesto exacto. 📋",
            
            // Servicios
            servicio: "Ofrecemos una amplia variedad de servicios de maestros de oficio: 🔧 Gasfitería, Electricidad, Albañilería, Carpintería, Pintura, Limpieza, Jardinería, Cerrajería, y más. Puedes buscar maestros por especialidad en nuestra página de búsqueda.",
            
            trabajo: "En Cachueleando On Fire puedes encontrar maestros para todo tipo de trabajos: reparaciones, instalaciones, mantenimiento, construcción, y servicios especializados. Todos nuestros maestros están verificados y tienen calificaciones de clientes anteriores. 🛠️",
            
            // Registro
            registro: "Registrarte es muy fácil: 1) Haz clic en 'Registrarse' en la parte superior, 2) Completa el formulario con tus datos, 3) Verifica tu cuenta, 4) ¡Listo! Ya puedes buscar y contratar maestros. Si eres maestro, también puedes registrarte para ofrecer tus servicios. 📝",
            
            registrarme: "Para registrarte como cliente, ve a la página de registro y completa el formulario. Si eres maestro de oficio, también puedes registrarte para ofrecer tus servicios en nuestra plataforma. El proceso es rápido y gratuito. 🚀",
            
            // Buscar maestros
            buscar: "Para buscar maestros, ve a la sección 'Buscar Maestros' en el menú principal. Allí puedes filtrar por especialidad, ubicación, disponibilidad y calificaciones. También puedes ver los perfiles de los maestros, sus trabajos anteriores y las opiniones de otros clientes. 🔍",
            
            encontrar: "Puedes encontrar maestros de varias formas: 1) Usa el buscador principal con filtros por especialidad, 2) Revisa los maestros destacados en la página principal, 3) Lee las calificaciones y comentarios de otros clientes para tomar la mejor decisión. ⭐",
            
            // Calificaciones
            calificacion: "Todas las calificaciones en nuestra plataforma son reales y verificadas. Los clientes pueden calificar a los maestros después de completar un trabajo en aspectos como puntualidad, calidad, trato y limpieza. Esto te ayuda a elegir el mejor maestro para tu necesidad. ⭐",
            
            // Verificación
            verificado: "Sí, todos los maestros en nuestra plataforma pasan por un proceso de verificación. Nuestro equipo de administración valida sus documentos, experiencia y referencias antes de aprobar su perfil. Esto garantiza que trabajas con profesionales confiables. ✅",
            
            seguro: "Sí, es seguro. Todos los maestros están verificados por nuestro equipo de administración. Además, puedes ver las calificaciones y comentarios de clientes anteriores antes de contratar. También puedes comunicarte con el maestro antes de confirmar el trabajo. 🔒",
            
            // Disponibilidad
            disponible: "Puedes ver la disponibilidad de cada maestro en tiempo real en su perfil. Los estados son: Disponible (puede trabajar ahora), Ocupado (tiene trabajos pendientes), o No disponible (no está trabajando en este momento). ⏰",
            
            // Contacto
            contacto: "Puedes contactar a los maestros directamente a través de sus perfiles. Una vez que encuentres un maestro que te interese, puedes ver su información de contacto y comunicarte con él para coordinar el trabajo. También puedes dejar una solicitud de trabajo. 📞",
            
            // Ubicación
            ubicacion: "Nuestros maestros trabajan principalmente en Lima y alrededores. Puedes filtrar la búsqueda por área o distrito para encontrar maestros cerca de tu ubicación. Muchos maestros también indican sus áreas de preferencia en sus perfiles. 📍",
            
            // Horarios
            horario: "Los horarios dependen de cada maestro y su disponibilidad. Algunos trabajan de lunes a viernes, otros también los fines de semana. Puedes ver la disponibilidad en tiempo real en cada perfil y contactar directamente para coordinar el horario que mejor te convenga. 🕐",
            
            // Pagos
            pago: "Los pagos se coordinan directamente con el maestro. Puedes acordar el método de pago (efectivo, transferencia, etc.) y cuándo realizar el pago (antes, durante o después del trabajo). Te recomendamos siempre acordar estos detalles antes de iniciar el trabajo. 💳",
            
            // Garantía
            garantia: "Cada maestro maneja sus propias políticas de garantía. Te recomendamos preguntar directamente al maestro sobre la garantía de su trabajo antes de contratarlo. También puedes revisar las calificaciones de otros clientes para conocer la calidad de su trabajo. 🛡️",
            
            // Ayuda general
            ayuda: "Estoy aquí para ayudarte con cualquier duda sobre nuestros servicios. Puedes preguntarme sobre precios, cómo buscar maestros, cómo registrarte, qué servicios ofrecemos, o cualquier otra pregunta. ¿Hay algo específico en lo que pueda ayudarte? 😊",
            
            hola: "¡Hola! 👋 Bienvenido a Cachueleando On Fire. Estoy aquí para ayudarte a encontrar el maestro de oficio perfecto para tu necesidad. ¿En qué puedo asistirte hoy?",
            
            gracias: "¡De nada! 😊 Si tienes más preguntas, no dudes en preguntarme. ¡Esperamos ayudarte a encontrar el maestro perfecto para tu trabajo! 🔥",
        };

        // Buscar respuesta basada en palabras clave
        for (const [keyword, response] of Object.entries(responses)) {
            if (message.includes(keyword)) {
                return response;
            }
        }

        // Respuestas por categorías más amplias
        if (message.includes('cuanto') || message.includes('precio') || message.includes('cuesta') || message.includes('tarifa')) {
            return responses.precio;
        }
        
        if (message.includes('que') && (message.includes('hacen') || message.includes('ofrecen') || message.includes('servicio'))) {
            return responses.servicio;
        }
        
        if (message.includes('como') && (message.includes('registrar') || message.includes('registro') || message.includes('inscribir'))) {
            return responses.registro;
        }
        
        if (message.includes('buscar') || message.includes('encontrar') || message.includes('donde')) {
            return responses.buscar;
        }

        // Respuesta por defecto
        return "Entiendo tu pregunta. En Cachueleando On Fire conectamos clientes con maestros de oficio profesionales. Puedo ayudarte con información sobre precios, servicios disponibles, cómo buscar maestros, cómo registrarte, o cualquier otra duda. ¿Podrías ser más específico sobre lo que necesitas? 🤔";
    }

    formatTime(date) {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Inicializar chatbot cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new CachueleandoChatbot();
});
