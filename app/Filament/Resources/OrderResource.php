<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Select, Repeater, Grid};
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\{TextColumn};

class OrderResource extends Resource
{
    // for auto calculate total start 
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total'] = collect($data['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return $data;
    }
    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data['total'] = collect($data['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return $data;
    }
    // for auto calculate total end
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('status')
                    ->label('Order Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->default('Pending')
                    ->required(),

                Repeater::make('items')
                    ->label('Order Items')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->hint('Single item price'),
                    ])
                    ->columns(3)
                    ->defaultItems(1)
                    ->collapsible(),
                TextInput::make('total')
                    ->label('Total Price')
                    ->prefix('৳')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(), // Required to save even if disabled
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer'),
                TextColumn::make('total')->money('bdt'),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'Pending',
                        'warning' => 'Processing',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),

                TextColumn::make('created_at')->label('Order Date')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'paid' => Pages\OrderPaid::route('/completed'),
            'pending' => Pages\OrderPending::route('/pending'),
            // 'canceled' => Pages\OrderCanceled::route('/canceled'),
        ];
    }
}
