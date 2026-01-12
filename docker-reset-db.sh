#!/bin/bash

# 🚀 PLN K3 Inventaris - Docker Database Reset Script
# This script will reset the database and create default users

echo "🐳 PLN K3 Inventaris - Database Reset"
echo "======================================"
echo ""
echo "⚠️  WARNING: This will DELETE ALL DATA in the database!"
echo ""
read -p "Are you sure you want to continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "❌ Cancelled."
    exit 1
fi

echo ""
echo "🔄 Starting database reset..."
echo ""

# Run migration fresh with seeder
docker compose exec frankenphp php artisan migrate:fresh --seed

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database reset successful!"
    echo ""
    echo "📋 Default Login Credentials:"
    echo "======================================"
    echo ""
    echo "🔑 SUPERADMIN:"
    echo "   Email: superadmin@pln.co.id"
    echo "   Username: superadmin"
    echo "   Password: super123"
    echo ""
    echo "👨‍💼 LEADER UPW2:"
    echo "   Email: leader.upw2@pln.co.id"
    echo "   Username: leader_upw2"
    echo "   Password: leader123"
    echo ""
    echo "👷 PETUGAS UPW2:"
    echo "   Email: petugas.upw2@pln.co.id"
    echo "   Username: petugas_upw2"
    echo "   Password: petugas123"
    echo ""
    echo "🔍 INSPECTOR:"
    echo "   Email: inspector@pln.co.id"
    echo "   Username: inspector"
    echo "   Password: inspector123"
    echo ""
    echo "======================================"
    echo "⚠️  Remember to change these passwords in production!"
    echo ""
else
    echo ""
    echo "❌ Database reset failed!"
    echo "Check the error messages above."
    echo ""
    echo "💡 Troubleshooting tips:"
    echo "   1. Make sure Docker containers are running: docker compose ps"
    echo "   2. Check database connection: docker compose logs mysql"
    echo "   3. Verify .env configuration inside container"
    echo ""
fi
