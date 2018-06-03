<?php

namespace Tests\Feature\Models;

use App\Certificate;
use App\CertificateCategory;
use App\Person;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CertificatesAndCategoriesTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategoriesMinimal()
    {
        $category = CertificateCategory::create([
            'name' => 'TestNaam'
        ]);

        $this->assertDatabaseHas('certificate_categories', [
            'id' => $category->id,
            'slug' => 'testnaam',
            'name' => 'TestNaam',
            'name_short' => null,
            'description' => null,
            'default_expire_years' => null,
            'is_required' => false
        ]);

        $found = CertificateCategory::find($category->id);

        $this->assertEquals($category->name, $found->name);
        $this->assertEquals('TestNaam', $category->name_short);
        $this->assertEquals('TestNaam', $found->name_short);
    }

    /**
     * Tests if all the attributes of a CertificateCategory are working.
     */
    public function testCategoriesFull() {
        $category = CertificateCategory::create([
            'name' => 'TestNaam Full',
            'name_short' => 'Naam Full',
            'description' => 'Een volledig ingevuld CertificateCategory',
            'default_expire_years' => 4,
            'is_required' => true
        ]);

        $this->assertEquals('Naam Full', $category->name_short);

        $this->assertDatabaseHas('certificate_categories', [
            'name' => 'TestNaam Full',
            'name_short' => 'Naam Full',
            'slug' => 'naam-full',
            'description' => 'Een volledig ingevuld CertificateCategory',
            'default_expire_years' => 4,
            'is_required' => true
        ]);
    }

    /**
     * Tests if the simple way of creating an certificate works
     */
    public function testCreateSimple() {
        $category = factory(CertificateCategory::class)->states('does_not_expire')->create();
        $person = factory(Person::class)->create();

        $certificate = $category->certificates()->create([
            'person_id' => $person->id,
        ]);

        $this->assertTrue($certificate->is_valid);

        $this->assertEquals($person->id, $certificate->person_id);
        $this->assertEquals($category->id, $certificate->category_id);
        $this->assertEquals(null, $certificate->examination_at);
        $this->assertEquals(null, $certificate->valid_at);
        $this->assertEquals(null, $certificate->expires_at);
        $this->assertEquals(null, $certificate->remarks);

        $this->assertDatabaseHas('certificates',[
            'id' => $certificate->id,
            'person_id' => $person->id,
            'category_id' => $category->id,
            'examination_at' => null,
            'passed' => true,
            'valid_at' => null,
            'expired_at' => null,
            'remarks' => null
        ]);

        $this->assertCount(1, Certificate::valid()->get());
    }

    public function testCreateAutomatic() {
        $category = factory(CertificateCategory::class)->create();
        $person = factory(Person::class)->create();

        $examination = Carbon::now()->subDays(2);

        $remarks = 'Alle datum velden zouden moeten worden ingevuld';

        $certificate = $category->certificates()->create([
            'person_id' => $person->id,
            'examination_at' => $examination,
            'remarks' => $remarks,
        ]);

        $expired = (clone $examination)->addYears($category->default_expire_years);

        $this->assertEquals($examination->toDateString(), $certificate->examination_at->toDateString());
        $this->assertEquals($examination->toDateString(), $certificate->valid_at->toDateString());
        $this->assertEquals($expired->toDateString(), $certificate->expired_at->toDateString());
        $this->assertEquals($remarks, $certificate->remarks);

        $this->assertTrue($certificate->is_valid);
        $this->assertCount(1, Certificate::valid()->get());

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'person_id' => $person->id,
            'category_id' => $category->id,
            'examination_at' => $examination->toDateString(),
            'passed' => true,
            'valid_at' => $examination->toDateString(),
            'expired_at' => $expired->toDateString(),
            'remarks' => $remarks
        ]);



        $certificateNotPassed = $category->certificates()->create([
            'person_id' => $person->id,
            'examination_at' => $examination,
            'passed' => false,
            'remarks' => 'Niet gehaald!',
        ]);

        $this->assertEquals($examination->toDateString(), $certificateNotPassed->examination_at->toDateString());
        $this->assertEquals(null, $certificateNotPassed->valid_at);
        $this->assertEquals(null, $certificateNotPassed->expired_at);
        $this->assertEquals('Niet gehaald!', $certificateNotPassed->remarks);

        $this->assertFalse($certificateNotPassed->is_valid);
        $this->assertCount(1, Certificate::valid()->get());

        $this->assertDatabaseHas('certificates', [
            'id' => $certificateNotPassed->id,
            'person_id' => $person->id,
            'category_id' => $category->id,
            'examination_at' => $examination->toDateString(),
            'passed' => false,
            'valid_at' => null,
            'expired_at' => null,
            'remarks' => 'Niet gehaald!'
        ]);

    }

    public function testCreateFull() {
        $category = factory(CertificateCategory::class)->create();
        $person = factory(Person::class)->create();

        $examination = Carbon::now()->subDays(4);
        $valid_at = Carbon::now()->subDays(2);
        $expired_at = Carbon::now()->addDays(2);

        $remarks = 'Zo volledig mogelijk ingevuld';

        $certificate = $person->certificates()->create([
            'category_id' => $category->id,
            'examination_at' => $examination,
            'passed' => true,
            'valid_at' => $valid_at,
            'expired_at' => $expired_at,
            'remarks' => $remarks
        ]);

        $this->assertTrue($certificate->passed);
        $this->assertNotNull($certificate->examination_at);
        $this->assertFalse($certificate->examination_at > Carbon::now());
        $this->assertNotNull($certificate->valid_at);
        $this->assertFalse($certificate->valid_at > Carbon::now());
        $this->assertNotNull($certificate->expired_at);
        $this->assertFalse($certificate->expired_at < Carbon::now());
        $this->assertTrue($certificate->is_valid);

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'person_id' => $person->id,
            'category_id' => $category->id,
            'examination_at' => $examination->toDateString(),
            'passed' => true,
            'valid_at' => $valid_at->toDateString(),
            'expired_at' => $expired_at->toDateString(),
            'remarks' => $remarks
        ]);

        $this->assertCount(1, Certificate::valid()->get());

        $certificate->passed = false;
        $certificate->save();

        $this->assertFalse($certificate->is_valid);

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'person_id' => $person->id,
            'category_id' => $category->id,
            'examination_at' => $examination->toDateString(),
            'passed' => false,
            'valid_at' => $valid_at->toDateString(),
            'expired_at' => $expired_at->toDateString(),
            'remarks' => $remarks
        ]);

        $this->assertCount(0, Certificate::valid()->get());
    }
}
