<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogisticFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed basic roles or data if needed, but we'll create users per test
    }

    /**
     * HU1: Login Exitoso
     */
    public function test_hu1_login_successful_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'ADMIN',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * HU1: Login Fallido
     */
    public function test_hu1_login_failed_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * HU3: Búsqueda de Medicamentos (ADMIN/FARMACIA/BODEGA/MEDICO)
     */
    public function test_hu3_search_medicines(): void
    {
        $user = User::factory()->create(['role' => 'FARMACIA']);
        Medicine::create(['name' => 'Paracetamol', 'sku' => 'PAR001']);
        Medicine::create(['name' => 'Ibuprofeno', 'sku' => 'IBU001']);

        $response = $this->actingAs($user)->get('/medicines?search=Paracetamol');

        $response->assertStatus(200);
        $response->assertSee('Paracetamol');
        $response->assertDontSee('Ibuprofeno');
    }

    /**
     * HU4: Perfil de Medicamento (Detalle)
     */
    public function test_hu4_view_medicine_detail(): void
    {
        $user = User::factory()->create(['role' => 'BODEGA']);
        $medicine = Medicine::create(['name' => 'Aspirina', 'sku' => 'ASP001', 'description' => 'Dolor de cabeza']);

        $response = $this->actingAs($user)->get("/medicines/{$medicine->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Aspirina');
        $response->assertSee('Dolor de cabeza');
    }

    /**
     * HU5: CRUD de Insumos (Creación)
     */
    public function test_hu5_create_supply(): void
    {
        $user = User::factory()->create(['role' => 'ADMIN']);

        $response = $this->actingAs($user)->post('/supplies', [
            'name' => 'Jeringa 10ml',
            'sku' => 'JER010',
            'stock_bodega' => 100,
            'stock_farmacia' => 0,
            'min_stock' => 10,
        ]);

        $response->assertRedirect('/supplies');
        $this->assertDatabaseHas('supplies', ['name' => 'Jeringa 10ml']);
    }

    /**
     * HU6: Médico crea receta
     */
    public function test_hu6_doctor_creates_prescription(): void
    {
        $doctor = User::factory()->create(['role' => 'MEDICO']);
        $patient = Patient::create(['full_name' => 'John Doe']);
        $medicine = Medicine::create(['name' => 'Amoxicilina', 'sku' => 'AMX']);

        $response = $this->actingAs($doctor)->post('/medico/prescriptions', [
            'patient_id' => $patient->id,
            'items' => [
                [
                    'medicine_id' => $medicine->id,
                    'quantity' => 1,
                    'dosage' => '500mg',
                    'frequency' => '8hrs',
                    'duration' => '7 days'
                ]
            ]
        ]);

        $response->assertRedirect(route('medico.prescriptions.index'));
        $this->assertDatabaseHas('prescriptions', ['patient_id' => $patient->id, 'doctor_id' => $doctor->id]);
        $this->assertDatabaseHas('prescription_items', ['item_id' => $medicine->id]);
    }

    /**
     * HU7: Bodega crea transferencia a Farmacia
     */
    public function test_hu7_bodega_creates_transfer_transaction(): void
    {
        $bodegaUser = User::factory()->create(['role' => 'BODEGA']);
        $medicine = Medicine::create(['name' => 'Venda', 'sku' => 'VEN', 'stock_bodega' => 100, 'stock_farmacia' => 0]);

        $response = $this->actingAs($bodegaUser)->post('/bodega/transfers', [
            'items' => [
                [
                    'item_id' => $medicine->id,
                    'type' => 'MEDICAMENTO',
                    'quantity' => 20
                ]
            ]
        ]);

        $response->assertRedirect(route('bodega.transfers.index'));

        // Verifica movimiento de stock
        $medicine->refresh();
        $this->assertEquals(80, $medicine->stock_bodega);
        $this->assertEquals(20, $medicine->stock_farmacia);

        // Verifica auditoría
        $this->assertDatabaseHas('stock_movements', [
            'item_id' => $medicine->id,
            'movement_type' => 'TRANSFER',
            'quantity' => 20
        ]);
    }

    /**
     * HU8: Farmacia entrega receta (Descargo de inventario)
     */
    public function test_hu8_pharmacy_delivers_prescription_decreases_stock(): void
    {
        $pharmacyUser = User::factory()->create(['role' => 'FARMACIA']);
        $doctor = User::factory()->create(['role' => 'MEDICO']);
        $patient = Patient::create(['full_name' => 'Jane Doe']);
        $medicine = Medicine::create(['name' => 'Ibuprofeno', 'sku' => 'IBU', 'stock_farmacia' => 10]);

        $prescription = Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'status' => 'PENDIENTE',
            'issued_at' => now(),
        ]);

        $prescription->items()->create([
            'item_type' => 'MEDICAMENTO',
            'item_id' => $medicine->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($pharmacyUser)
            ->from(route('farmacia.deliveries.index'))
            ->post("/farmacia/deliveries/{$prescription->id}");

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $response->assertRedirect(route('farmacia.deliveries.index'));

        // Verifica stock descontado
        $medicine->refresh();
        $this->assertEquals(9, $medicine->stock_farmacia);

        // Verifica estado receta
        $prescription->refresh();
        $this->assertEquals('ENTREGADA', $prescription->status);
    }

    /**
     * HU10: Admin gestiona usuarios
     */
    public function test_hu10_admin_creates_user(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Doctor',
            'email' => 'doc@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'MEDICO',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'doc@test.com', 'role' => 'MEDICO']);
    }

    /**
     * Seguridad: Rol no autorizado es rechazado
     */
    public function test_unauthorized_role_cannot_access_admin_route(): void
    {
        $medico = User::factory()->create(['role' => 'MEDICO']);

        $response = $this->actingAs($medico)->get('/admin/users');

        $response->assertStatus(403);
    }
}
