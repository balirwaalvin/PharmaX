// Real-time Admin Dashboard JavaScript
// This handles live updates and interactive functionality for the admin panel

class AdminDashboard {
    constructor() {
        this.updateInterval = 10000; // 10 seconds for real-time updates
        this.isUpdating = false;
        this.autoRefreshEnabled = true;
        this.intervalId = null;
        
        console.log('AdminDashboard initialized');
    }
    
    init() {
        this.setupEventListeners();
        this.startRealTimeUpdates();
        this.loadInitialData();
        console.log('Admin dashboard started');
    }
    
    setupEventListeners() {
        // Auto-refresh toggle
        const autoRefreshToggle = document.getElementById('autoRefreshToggle');
        if (autoRefreshToggle) {
            autoRefreshToggle.addEventListener('change', (e) => {
                this.autoRefreshEnabled = e.target.checked;
                if (this.autoRefreshEnabled) {
                    this.startRealTimeUpdates();
                } else {
                    this.stopRealTimeUpdates();
                }
            });
        }
        
        // Escape key to close notifications
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideNotification();
            }
        });
    }
    
    startRealTimeUpdates() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
        
        if (this.autoRefreshEnabled) {
            this.intervalId = setInterval(() => {
                this.refreshData();
            }, this.updateInterval);
            console.log('Real-time updates started');
        }
    }
    
    stopRealTimeUpdates() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
            console.log('Real-time updates stopped');
        }
    }
    
    async refreshData() {
        if (this.isUpdating) return;
        
        this.isUpdating = true;
        
        try {
            // Fetch latest data from API
            const response = await fetch('admin_api.php?action=get_dashboard_data');
            const data = await response.json();
            
            if (data.success) {
                this.updateUserStats(data.data.stats);
                this.updateOrdersTable(data.data.orders);
                this.updateMessagesTable(data.data.messages);
                this.updateActivityFeed(data.data.activity);
                
                console.log('Dashboard data refreshed');
            } else {
                console.error('Failed to refresh data:', data.message);
            }
        } catch (error) {
            console.error('Error refreshing data:', error);
        } finally {
            this.isUpdating = false;
        }
    }
    
    loadInitialData() {
        // Load activity feed on page load
        this.loadActivityFeed();
    }
    
    updateUserStats(stats) {
        const totalUsers = document.getElementById('totalUsers');
        const activeUsers = document.getElementById('activeUsers');
        const deactivatedUsers = document.getElementById('deactivatedUsers');
        
        if (totalUsers) totalUsers.textContent = stats.total_users || 0;
        if (activeUsers) activeUsers.textContent = stats.active_users || 0;
        if (deactivatedUsers) deactivatedUsers.textContent = stats.inactive_users || 0;
    }
    
    updateOrdersTable(orders) {
        const tableBody = document.querySelector('#ordersTable tbody');
        if (!tableBody) return;
        
        let html = '';
        
        if (orders && orders.length > 0) {
            orders.forEach(order => {
                const statusClass = order.order_status === 'Shipped' ? 'status-shipped' : 'status-pending';
                const paymentClass = order.payment_status === 'Paid' ? 'status-paid' : 'status-unpaid';
                
                let orderTypeHtml = order.order_type;
                if (order.order_type === 'Prescription' && order.prescription_url) {
                    orderTypeHtml = `<a href="./Images/PrescriptionOrders/${order.prescription_url}" target="_blank">Prescription</a>`;
                }
                
                html += `
                    <tr data-order-id="${order.order_id}">
                        <td><span class="ordertb_title">ID:</span> ${order.order_id}</td>
                        <td><span class="ordertb_title">Customer:</span> ${order.user_name}</td>
                        <td><span class="ordertb_title">Date:</span> ${order.order_date}</td>
                        <td><span class="ordertb_title">Status:</span> <span class="status-badge ${statusClass}">${order.order_status}</span></td>
                        <td><span class="ordertb_title">Total:</span> ${order.Order_total}</td>
                        <td><span class="status-badge ${paymentClass}">${order.payment_status}</span></td>
                        <td>${orderTypeHtml}</td>
                        <td class="action-cell">
                            ${order.order_status === 'Shipped' 
                                ? '<button class="action_btn" disabled>Already Shipped</button>'
                                : `<button class="action_btn bg" onclick="adminDashboard.updateOrderStatus('${order.order_id}', 'Shipped')">Mark as Shipped</button>`
                            }
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="8">No Orders Found</td></tr>';
        }
        
        tableBody.innerHTML = html;
    }
    
    updateMessagesTable(messages) {
        const tableBody = document.querySelector('#messagesTable tbody');
        if (!tableBody) return;
        
        let html = '';
        
        if (messages && messages.length > 0) {
            messages.forEach(message => {
                const uploadsUrl = message.Uploads_url 
                    ? `<a href="./Images/PrescriptionMessage/${message.Uploads_url}" target="_blank">Link</a>`
                    : '----';
                
                html += `
                    <tr data-message-id="${message.message_id}">
                        <td>${message.message_id}</td>
                        <td>${message.user_name}</td>
                        <td>${message.message_text}</td>
                        <td>${uploadsUrl}</td>
                        <td><input class="Reply_in" type="text" placeholder="Reply" id="reply_${message.message_id}"></td>
                        <td><button type="button" class="Submit_rply" onclick="adminDashboard.replyToMessage('${message.message_id}')">Submit</button></td>
                        <td><button type="button" class="Delete_rply" onclick="adminDashboard.deleteMessage('${message.message_id}')">Delete</button></td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="7">No Messages Found</td></tr>';
        }
        
        tableBody.innerHTML = html;
    }
    
    updateActivityFeed(activities) {
        const activityFeed = document.getElementById('activityFeed');
        if (!activityFeed) return;
        
        let html = '';
        
        if (activities && activities.length > 0) {
            activities.slice(-10).reverse().forEach(activity => {
                html += `
                    <div class="activity-item">
                        <div class="activity-time">${activity.timestamp}</div>
                        <div>${activity.activity}</div>
                    </div>
                `;
            });
        } else {
            html = '<div class="activity-item">No recent activity</div>';
        }
        
        activityFeed.innerHTML = html;
    }
    
    async loadActivityFeed() {
        try {
            const response = await fetch('admin_api.php?action=get_activity');
            const data = await response.json();
            
            if (data.success) {
                this.updateActivityFeed(data.data);
            }
        } catch (error) {
            console.error('Error loading activity feed:', error);
        }
    }
    
    // User management functions
    async checkUserStatus() {
        const userInput = document.getElementById('userInput');
        const userStatus = document.getElementById('userStatus');
        const username = userInput.value.trim();
        
        if (!username) {
            userStatus.textContent = 'Please enter a username';
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('action', 'check_user_status');
            formData.append('username', username);
            
            const response = await fetch('admin_api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                userStatus.textContent = data.data.status || 'User not found';
            } else {
                userStatus.textContent = data.message || 'Error checking user';
            }
        } catch (error) {
            console.error('Error checking user status:', error);
            userStatus.textContent = 'Error checking user';
        }
    }
    
    async updateUserStatus(action) {
        const userInput = document.getElementById('userInput');
        const userStatus = document.getElementById('userStatus');
        const username = userInput.value.trim();
        
        if (!username) {
            this.showNotification('Please enter a username', 'error');
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('ajax_action', 'update_user_status');
            formData.append('username', username);
            formData.append('action', action);
            
            const response = await fetch('admin_DB.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification(data.message, 'success');
                userStatus.textContent = action === 'delete' ? 'User deleted' : 
                                       action === 'activate' ? 'Active' : 'Inactive';
                this.refreshData(); // Refresh the dashboard
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error updating user status:', error);
            this.showNotification('Error updating user status', 'error');
        }
    }
    
    async updateOrderStatus(orderId, status) {
        try {
            const formData = new FormData();
            formData.append('ajax_action', 'update_order_status');
            formData.append('order_id', orderId);
            formData.append('status', status);
            
            const response = await fetch('admin_DB.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshData(); // Refresh the dashboard
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error updating order status:', error);
            this.showNotification('Error updating order status', 'error');
        }
    }
    
    async replyToMessage(messageId) {
        const replyInput = document.getElementById(`reply_${messageId}`);
        const reply = replyInput.value.trim();
        
        if (!reply) {
            this.showNotification('Please enter a reply', 'error');
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('ajax_action', 'reply_message');
            formData.append('message_id', messageId);
            formData.append('reply', reply);
            
            const response = await fetch('admin_DB.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification(data.message, 'success');
                replyInput.value = '';
                this.refreshData(); // Refresh the dashboard
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error replying to message:', error);
            this.showNotification('Error sending reply', 'error');
        }
    }
    
    async deleteMessage(messageId) {
        if (!confirm('Are you sure you want to delete this message?')) {
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('ajax_action', 'delete_message');
            formData.append('message_id', messageId);
            
            const response = await fetch('admin_DB.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshData(); // Refresh the dashboard
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error deleting message:', error);
            this.showNotification('Error deleting message', 'error');
        }
    }
    
    // Notification system
    showNotification(message, type = 'info') {
        const notification = document.getElementById('notification');
        if (!notification) return;
        
        notification.textContent = message;
        notification.className = `notification ${type}`;
        notification.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            this.hideNotification();
        }, 5000);
    }
    
    hideNotification() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    }
}

// Initialize the dashboard when DOM is loaded
let adminDashboard;
document.addEventListener('DOMContentLoaded', function() {
    adminDashboard = new AdminDashboard();
});

// Export for global access
window.adminDashboard = adminDashboard;
