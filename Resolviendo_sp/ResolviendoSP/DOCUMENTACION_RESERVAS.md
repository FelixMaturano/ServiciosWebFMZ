# Documentación - ReservaController

## Configuración

- **Hotel sucreGran**: Puerto 8001 (http://localhost:8001)
- **Intermediador Reservas**: Puerto por defecto (http://localhost:8000 o tu puerto)

---

## Endpoints del Intermediador de Reservas

### 1. Obtener todas las reservas
```
GET /api/reservas
```
**Respuesta:**
```json
[
  {
    "id": 1,
    "codigoHabitacion": "101",
    "hotel_origen": "Hotel sucreGran",
    "cliente_cedula": "1234567890",
    "cliente_nombre": "Juan Pérez",
    "fecha_inicio": "2026-06-01",
    "fecha_final": "2026-06-05",
    "monto": 500.00,
    "estado": "confirmada",
    "created_at": "2026-05-31T12:00:00Z",
    "updated_at": "2026-05-31T12:00:00Z"
  }
]
```

---

### 2. Crear una nueva reserva
```
POST /api/reservas
Content-Type: application/json
```

**Datos requeridos:**
```json
{
  "codigoHabitacion": "101",
  "hotel_origen": "Hotel sucreGran",
  "cliente_cedula": "1234567890",
  "cliente_nombre": "Juan Pérez",
  "fecha_inicio": "2026-06-01",
  "fecha_final": "2026-06-05",
  "monto": 500.00
}
```

**Respuesta exitosa (201):**
```json
{
  "codigoHabitacion": "101",
  "hotel_origen": "Hotel sucreGran",
  "cliente_cedula": "1234567890",
  "cliente_nombre": "Juan Pérez",
  "fecha_inicio": "2026-06-01",
  "fecha_final": "2026-06-05",
  "monto": 500,
  "estado": "confirmada",
  "updated_at": "2026-05-31T12:00:00Z",
  "created_at": "2026-05-31T12:00:00Z",
  "id": 1
}
```

**Lo que sucede internamente:**
1. Valida los datos
2. Verifica que la habitación existe en el hotel sucreGran
3. Verifica que la habitación esté disponible
4. Crea la reserva
5. Actualiza el estado de la habitación en el hotel a "no"

---

### 3. Obtener una reserva específica
```
GET /api/reservas/{id}
```

**Ejemplo:**
```
GET /api/reservas/1
```

**Respuesta:**
```json
{
  "id": 1,
  "codigoHabitacion": "101",
  "hotel_origen": "Hotel sucreGran",
  "cliente_cedula": "1234567890",
  "cliente_nombre": "Juan Pérez",
  "fecha_inicio": "2026-06-01",
  "fecha_final": "2026-06-05",
  "monto": 500.00,
  "estado": "confirmada",
  "created_at": "2026-05-31T12:00:00Z",
  "updated_at": "2026-05-31T12:00:00Z"
}
```

---

### 4. Actualizar una reserva
```
PUT /api/reservas/{id}
Content-Type: application/json
```

**Datos opcionales:**
```json
{
  "estado": "cancelada",
  "monto": 450.00,
  "fecha_inicio": "2026-06-02",
  "fecha_final": "2026-06-06"
}
```

**Estados válidos:** `confirmada`, `cancelada`, `completada`

**Nota:** Si cambias el estado a "cancelada", la habitación se liberará automáticamente en el hotel.

---

### 5. Eliminar una reserva
```
DELETE /api/reservas/{id}
```

**Respuesta:**
```json
{
  "mensaje": "Reserva eliminada correctamente"
}
```

**Lo que sucede:** Se libera la habitación en el hotel automáticamente.

---

### 6. Obtener habitaciones disponibles del hotel
```
GET /api/hotel/habitaciones
```

**Respuesta:**
```json
{
  "total": 10,
  "disponibles": 7,
  "habitaciones": [
    {
      "codigoHabitacion": "101",
      "tipo": "Doble",
      "capacidad": "2",
      "tarifa": 100.00,
      "disponible": "si",
      "created_at": "2026-05-31T12:00:00Z",
      "updated_at": "2026-05-31T12:00:00Z"
    },
    {
      "codigoHabitacion": "102",
      "tipo": "Suite",
      "capacidad": "4",
      "tarifa": 200.00,
      "disponible": "si",
      "created_at": "2026-05-31T12:00:00Z",
      "updated_at": "2026-05-31T12:00:00Z"
    }
  ]
}
```

---

## Flujo de operación

### Crear una Reserva
```
Cliente → Intermediador → Hotel sucreGran
                ↓
         Valida habitación
                ↓
         Verifica disponibilidad
                ↓
         Crea reserva localmente
                ↓
         Actualiza estado en Hotel
```

### Cancelar una Reserva
```
Cliente → Intermediador → Hotel sucreGran
                ↓
         Actualiza estado a "cancelada"
                ↓
         Libera habitación en Hotel
```

---

## Códigos de respuesta

| Código | Significado |
|--------|-------------|
| 200 | OK - Operación exitosa |
| 201 | Created - Recurso creado |
| 404 | Not Found - Recurso no encontrado |
| 409 | Conflict - Habitación no disponible |
| 422 | Unprocessable Entity - Validación fallida |
| 500 | Server Error - Error del servidor |

---

## Ejemplo con cURL

### Crear una reserva
```bash
curl -X POST http://localhost:8000/api/reservas \
  -H "Content-Type: application/json" \
  -d '{
    "codigoHabitacion": "101",
    "hotel_origen": "Hotel sucreGran",
    "cliente_cedula": "1234567890",
    "cliente_nombre": "Juan Pérez",
    "fecha_inicio": "2026-06-01",
    "fecha_final": "2026-06-05",
    "monto": 500.00
  }'
```

### Obtener habitaciones disponibles
```bash
curl http://localhost:8000/api/hotel/habitaciones
```

### Cancelar una reserva
```bash
curl -X PUT http://localhost:8000/api/reservas/1 \
  -H "Content-Type: application/json" \
  -d '{"estado": "cancelada"}'
```

---

## Pasos para ejecutar

1. **Hotel sucreGran** (Puerto 8001):
```bash
cd 1_Hotel_sucreGran
php artisan serve --port=8001
```

2. **Intermediador de Reservas** (Puerto 8000):
```bash
cd 2_intermediador_Reservas
php artisan migrate  # Si es la primera vez
php artisan serve
```

3. **Probar los endpoints** usando Postman, Insomnia o cURL

---

## Notas importantes

- La conexión entre servicios es síncrona (HTTP)
- Si el hotel no está disponible, fallará la creación/cancelación de reservas
- La tabla `reservas` debe estar creada antes de usarla
- Asegúrate de que ambos proyectos estén ejecutándose en los puertos correctos
