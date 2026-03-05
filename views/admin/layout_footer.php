            </div><!-- /.admin-content -->
        </main><!-- /.admin-main -->

    </div><!-- /.admin-body -->
</div><!-- /.admin-shell -->

<!-- ═══ GLOBAL CUSTOM ALERT MODAL ═══ -->
<style>
.custom-alert-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999; opacity: 0; visibility: hidden; transition: opacity 0.3s;
}
.custom-alert-overlay.show { opacity: 1; visibility: visible; }
.custom-alert-box {
    background: #1a1b26; border-radius: 12px; padding: 25px;
    width: 90%; max-width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    border: 1px solid #2f334d; text-align: center;
    transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.custom-alert-overlay.show .custom-alert-box { transform: translateY(0); }
.custom-alert-icon { font-size: 3rem; margin-bottom: 15px; }
.custom-alert-message { font-size: 1rem; color: #a9b1d6; margin-bottom: 20px; line-height: 1.5; white-space: pre-wrap; }
.custom-alert-btn {
    background: #3d59a1; color: white; border: none;
    padding: 10px 25px; border-radius: 6px; font-weight: 600; cursor: pointer;
    transition: background 0.2s;
}
.custom-alert-btn:hover { background: #2f4376; }
</style>

<div id="globalCustomAlert" class="custom-alert-overlay">
    <div class="custom-alert-box">
        <div class="custom-alert-icon" id="customAlertIcon">ℹ️</div>
        <div class="custom-alert-message" id="customAlertMessage"></div>
        <button class="custom-alert-btn" onclick="closeCustomAlert()">Entendido</button>
    </div>
</div>

<div id="globalCustomConfirm" class="custom-alert-overlay">
    <div class="custom-alert-box">
        <div class="custom-alert-icon">⚠️</div>
        <div class="custom-alert-message" id="customConfirmMessage"></div>
        <div style="display:flex; gap:10px; justify-content:center; margin-top: 20px;">
            <button class="custom-alert-btn" style="background: #f87171;" onclick="closeCustomConfirm()">Cancelar</button>
            <button class="custom-alert-btn" style="background: #3d59a1;" id="customConfirmOkBtn">Confirmar</button>
        </div>
    </div>
</div>

<script>
function showCustomAlert(message, type = 'info') {
    const overlay = document.getElementById('globalCustomAlert');
    const msgBox = document.getElementById('customAlertMessage');
    const iconBox = document.getElementById('customAlertIcon');
    
    if (message.includes('Error') || type === 'error') iconBox.innerHTML = '❌';
    else if (message.includes('éxito') || message.includes('xito') || type === 'success') iconBox.innerHTML = '✅';
    else if (message.includes('Atención') || message.includes('Importante') || type === 'warning') iconBox.innerHTML = '⚠️';
    else iconBox.innerHTML = 'ℹ️';

    msgBox.textContent = message;
    overlay.classList.add('show');
}
function closeCustomAlert() {
    document.getElementById('globalCustomAlert').classList.remove('show');
}
window.alert = function(msg) { showCustomAlert(msg); };

function showCustomConfirm(message, callback) {
    const overlay = document.getElementById('globalCustomConfirm');
    const msgBox = document.getElementById('customConfirmMessage');
    const okBtn = document.getElementById('customConfirmOkBtn');
    
    msgBox.textContent = message;
    overlay.classList.add('show');
    
    const newOkBtn = okBtn.cloneNode(true);
    okBtn.parentNode.replaceChild(newOkBtn, okBtn);
    
    newOkBtn.addEventListener('click', function() {
        closeCustomConfirm();
        if(callback) callback();
    });
}
function closeCustomConfirm() {
    document.getElementById('globalCustomConfirm').classList.remove('show');
}
function confirmCustom(event, message) {
    event.preventDefault();
    const targetUrl = event.currentTarget.href;
    const targetForm = event.currentTarget.closest('form');
    
    showCustomConfirm(message, function() {
        if (targetUrl && targetUrl !== "" && targetUrl !== "#" && !targetUrl.startsWith("javascript")) {
            window.location.href = targetUrl;
        } else if (targetForm) {
            targetForm.submit();
        }
    });
    return false;
}
</script>
<!-- Bootstrap JS (needed for modals and interactions) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo URL_BASE; ?>public/js/admin.js"></script>
</body>
</html>
