
const OfflineSync = (() => {

    const DB_NAME = "pecuaria_offline_db";
    const STORE_NAME = "fila_sincronizacao";
    const DB_VERSION = 1;

    function abrirBanco() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, DB_VERSION);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: "id", autoIncrement: true });
                }
            };

            request.onsuccess = (event) => resolve(event.target.result);
            request.onerror = (event) => reject(event.target.error);
        });
    }

    async function salvarPendente(item) {
        const db = await abrirBanco();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, "readwrite");
            tx.objectStore(STORE_NAME).add(item);
            tx.oncomplete = () => resolve();
            tx.onerror = (e) => reject(e.target.error);
        });
    }

    async function listarPendentes() {
        const db = await abrirBanco();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, "readonly");
            const request = tx.objectStore(STORE_NAME).getAll();
            request.onsuccess = () => resolve(request.result);
            request.onerror = (e) => reject(e.target.error);
        });
    }

    async function removerPendente(id) {
        const db = await abrirBanco();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, "readwrite");
            tx.objectStore(STORE_NAME).delete(id);
            tx.oncomplete = () => resolve();
            tx.onerror = (e) => reject(e.target.error);
        });
    }

    function mostrarAviso(texto, tipo = "warning") {
        let aviso = document.getElementById("offline-aviso");
        if (!aviso) {
            aviso = document.createElement("div");
            aviso.id = "offline-aviso";
            aviso.style.position = "fixed";
            aviso.style.bottom = "20px";
            aviso.style.right = "20px";
            aviso.style.zIndex = "9999";
            aviso.style.padding = "12px 18px";
            aviso.style.borderRadius = "8px";
            aviso.style.boxShadow = "0 2px 10px rgba(0,0,0,0.2)";
            aviso.style.fontSize = "0.9rem";
            document.body.appendChild(aviso);
        }
        aviso.textContent = texto;
        aviso.style.background = tipo === "success" ? "#198754" : (tipo === "danger" ? "#dc3545" : "#ffc107");
        aviso.style.color = tipo === "warning" ? "#000" : "#fff";
        aviso.style.display = "block";

        clearTimeout(aviso._timeout);
        aviso._timeout = setTimeout(() => { aviso.style.display = "none"; }, 5000);
    }

    async function tentarEnviar(url, formData) {
        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData
            });

            return response.ok || response.type === "opaqueredirect";
        } catch (erro) {
            
            return false;
        }
    }

    async function sincronizarPendentes() {
        if (!navigator.onLine) return;

        const pendentes = await listarPendentes();
        if (pendentes.length === 0) return;

        mostrarAviso(`Sincronizando ${pendentes.length} registro(s) pendente(s)...`, "warning");

        let sucesso = 0;
        for (const item of pendentes) {
            const formData = new FormData();
            for (const chave in item.dados) {
                formData.append(chave, item.dados[chave]);
            }
            const ok = await tentarEnviar(item.url, formData);
            if (ok) {
                await removerPendente(item.id);
                sucesso++;
            }
        }

        if (sucesso > 0) {
            mostrarAviso(`${sucesso} registro(s) sincronizado(s) com sucesso!`, "success");
            atualizarContadorPendentes();
        }
    }

    async function atualizarContadorPendentes() {
        const pendentes = await listarPendentes();
        const badge = document.getElementById("offline-pendentes-badge");
        if (badge) {
            if (pendentes.length > 0) {
                badge.textContent = pendentes.length;
                badge.style.display = "inline-block";
            } else {
                badge.style.display = "none";
            }
        }
    }

    function interceptarFormulario(form) {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const url = form.getAttribute("action");
            const formData = new FormData(form);

            if (navigator.onLine) {
                const ok = await tentarEnviar(url, formData);
                if (ok) {
                    
                    window.location.href = url;
                    return;
                }
            }

            const dados = {};
            for (const [chave, valor] of formData.entries()) {
                dados[chave] = valor;
            }

            await salvarPendente({ url, dados, criado_em: new Date().toISOString() });
            mostrarAviso("Sem conexão. Dados salvos no dispositivo e serão enviados automaticamente quando a internet voltar.", "warning");
            atualizarContadorPendentes();

            if (form.dataset.offlineRedirect) {
                setTimeout(() => { window.location.href = form.dataset.offlineRedirect; }, 2500);
            }
        });
    }

    function iniciar() {
        document.querySelectorAll("form[data-offline-form]").forEach(interceptarFormulario);

        window.addEventListener("online", () => {
            mostrarAviso("Conexão restabelecida. Sincronizando...", "success");
            sincronizarPendentes();
        });

        window.addEventListener("offline", () => {
            mostrarAviso("Você está offline. Os próximos registros serão salvos localmente.", "warning");
        });

        atualizarContadorPendentes();
        if (navigator.onLine) sincronizarPendentes();
    }

    document.addEventListener("DOMContentLoaded", iniciar);

    return { sincronizarPendentes, listarPendentes };
})();