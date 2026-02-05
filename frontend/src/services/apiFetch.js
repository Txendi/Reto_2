const API_BASE = import.meta.env.VITE_API_URL

export async function apiFetch(path, options = {}) {
  const url = `${API_BASE}${path.startsWith('/') ? '' : '/'}${path}`

  const res = await fetch(url, {
    ...options,
    credentials: 'include', // si usas cookies/sesión
    headers: {
      ...(options.headers || {}),
       'Content-Type': 'application/json', // solo si mandas JSON
    },
  })

  if (!res.ok) {
    const text = await res.text().catch(() => '')
    throw new Error(`API ${res.status}: ${text}`)
  }

  // Si tu API siempre devuelve JSON:
  return res.json()
}
