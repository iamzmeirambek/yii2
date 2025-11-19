<?php

namespace backend\tests\unit\services;

use backend\modules\api\services\BookService;
use common\models\Book;
use Yii;
use Codeception\Test\Unit;

class BookServiceTest extends Unit
{
    private BookService $service;

    protected function _before(): void
    {
        $this->service = new BookService();

        Book::deleteAll();

        Yii::$app->user->id = 1;
    }

    public function testCreateSuccess()
    {
        $data = [
            'title' => 'Test Book',
            'author' => 'John Doe',
        ];

        $book = $this->service->create($data);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['author'], $book->author);
        $this->assertEquals(1, $book->created_by);
        $this->assertNotEmpty($book->created_at);
        $this->assertNotEmpty($book->updated_at);
    }

    public function testUpdateSuccess()
    {
        // Сначала создаём книгу
        $book = new Book();
        $book->title = 'Old Title';
        $book->author = 'Old Author';
        $book->created_by = 1;
        $book->created_at = time();
        $book->updated_at = time();
        $book->save();

        $data = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
        ];

        $updatedBook = $this->service->update($book->id, $data);

        $this->assertEquals('Updated Title', $updatedBook->title);
        $this->assertEquals('Updated Author', $updatedBook->author);
        $this->assertGreaterThan($book->updated_at, $updatedBook->updated_at);
    }

    public function testDeleteSuccess()
    {
        // Создаём книгу
        $book = new Book();
        $book->title = 'Book to delete';
        $book->author = 'Author';
        $book->created_by = 1;
        $book->created_at = time();
        $book->updated_at = time();
        $book->save();

        $this->service->delete($book->id);

        $this->assertNull(Book::findOne($book->id));
    }

    public function testUpdateNotFound()
    {
        $this->expectException(\DomainException::class);
        $this->service->update(9999, ['title' => 'Test']);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(\DomainException::class);
        $this->service->delete(9999);
    }
}
