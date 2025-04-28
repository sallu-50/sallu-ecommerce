<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, Textarea, FileUpload, RichEditor, Toggle};
use Filament\Tables\Columns\{TextColumn, ImageColumn, BooleanColumn};
use App\Filament\Resources\ProductResource\Pages\ListProducts;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),

                TextInput::make('name')
                    ->label('Brand Name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description'),

                TextInput::make('price')
                    ->numeric()
                    ->required(),

                TextInput::make('discount_price')
                    ->numeric()
                    ->nullable()
                    ->label('Discount Price'),

                TextInput::make('stock')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_featured')
                    ->label('Featured Product')
                    ->default(false),

                TextInput::make('sold')
                    ->numeric()
                    ->default(0)
                    ->label('Sold Count'),

                FileUpload::make('image')
                    ->image()
                    ->directory('products') // will be stored in storage/app/public/products
                    ->imagePreviewHeight('100')
                    ->downloadable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('price')->money('bdt'),
                TextColumn::make('stock'),
                BooleanColumn::make('is_featured')->label('Featured'),
                TextColumn::make('sold')->label('Sold Count'),
            ])
            ->filters([
                // Add any filters here if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
