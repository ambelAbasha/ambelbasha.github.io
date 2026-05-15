#!/bin/bash

# Color Codes
NC='\033[0m'       # No Color (reset)
RED='\033[0;31m'    # Red
GREEN='\033[0;32m'  # Green
YELLOW='\033[1;33m' # Yellow
BLUE='\033[0;34m'   # Blue
CYAN='\033[0;36m'   # Cyan
PURPLE='\033[0;35m' # Purple
BOLD='\033[1m'      # Bold

# Function Definitions

# 1. System Update and Patching
update_system() {
    echo -e "${CYAN}Updating system...${NC}"
    sudo apt-get update && sudo apt-get upgrade -y
    sudo apt-get dist-upgrade -y
    echo -e "${GREEN}System update complete!${NC}"
}

# 2. User and Group Management
manage_users() {
    echo -e "${YELLOW}Managing users...${NC}"
    read -p "Enter username to add: " username
    sudo useradd -m -s /bin/bash "$username"
    sudo passwd "$username"
    echo -e "${GREEN}User $username added successfully.${NC}"
}

# 3. Firewall Configuration (Using UFW)
configure_firewall() {
    echo -e "${BLUE}Configuring firewall...${NC}"
    sudo ufw enable
    sudo ufw default deny incoming
    sudo ufw default allow outgoing
    sudo ufw allow ssh
    sudo ufw allow http
    sudo ufw allow https
    sudo ufw reload
    echo -e "${GREEN}Firewall configured!${NC}"
}

# 4. Install Fail2Ban (Intrusion Prevention)
install_fail2ban() {
    echo -e "${PURPLE}Installing Fail2Ban...${NC}"
    sudo apt-get install fail2ban -y
    sudo systemctl enable fail2ban
    sudo systemctl start fail2ban
    echo -e "${GREEN}Fail2Ban installed and started!${NC}"
}

# 5. Enable SELinux (if using RedHat-based system, or AppArmor for Debian-based)
enable_selinux() {
    if [ -f /etc/selinux/config ]; then
        echo -e "${CYAN}Enabling SELinux...${NC}"
        sudo sed -i 's/^SELINUX=.*/SELINUX=enforcing/' /etc/selinux/config
        sudo setenforce 1
        echo -e "${GREEN}SELinux enabled!${NC}"
    else
        echo -e "${RED}SELinux configuration file not found, skipping SELinux setup.${NC}"
    fi
}

enable_apparmor() {
    echo -e "${CYAN}Enabling AppArmor...${NC}"
    sudo systemctl enable apparmor
    sudo systemctl start apparmor
    echo -e "${GREEN}AppArmor enabled!${NC}"
}

# 6. Disk Encryption (Using LUKS for full disk encryption - This should be used carefully!)
enable_encryption() {
    echo -e "${RED}Disk encryption setup is a sensitive task. This must be done during installation or on a new partition.${NC}"
    echo -e "${RED}Make sure you understand the implications of enabling full disk encryption before proceeding.${NC}"
    echo -e "${YELLOW}Skipping disk encryption setup in this script for safety.${NC}"
}

# 7. Backup System
backup_system() {
    echo -e "${CYAN}Backing up system...${NC}"
    read -p "Enter the directory to back up: " directory
    read -p "Enter the backup destination (e.g., /mnt/backup/): " backup_location
    sudo rsync -av --delete "$directory" "$backup_location"
    echo -e "${GREEN}Backup completed!${NC}"
}

# 8. File Permission and Ownership Fixes
fix_permissions() {
    echo -e "${BLUE}Fixing file permissions and ownership...${NC}"
    sudo chmod -R go-rwx /home/* /etc/* /var/*  # Restrict permissions
    sudo chown -R root:root /etc/* /var/*        # Ownership fix
    echo -e "${GREEN}Permissions and ownership fixed!${NC}"
}

# 9. SSH Key-Based Authentication Setup
setup_ssh_key_auth() {
    echo -e "${YELLOW}Setting up SSH key authentication...${NC}"
    read -p "Enter the path to the public SSH key: " pubkey_path
    if [ -f "$pubkey_path" ]; then
        mkdir -p ~/.ssh
        cat "$pubkey_path" >> ~/.ssh/authorized_keys
        chmod 600 ~/.ssh/authorized_keys
        echo -e "${GREEN}SSH key-based authentication set up!${NC}"
    else
        echo -e "${RED}Public key file not found, skipping setup.${NC}"
    fi
}

# 10. Audit and Monitoring (Install auditd)
install_auditd() {
    echo -e "${PURPLE}Installing auditd...${NC}"
    sudo apt-get install auditd audispd-plugins -y
    sudo systemctl enable auditd
    sudo systemctl start auditd
    echo -e "${GREEN}Auditd installed and running!${NC}"
}

# 11. Display Menu
display_menu() {
    clear
    echo -e "${BOLD}${GREEN}Linux Security Hardening Script${NC}"
    echo -e "${BOLD}${CYAN}================================${NC}"
    echo -e "1) ${RED}Update System${NC}"
    echo -e "2) ${YELLOW}Manage Users${NC}"
    echo -e "3) ${BLUE}Configure Firewall${NC}"
    echo -e "4) ${PURPLE}Install Fail2Ban${NC}"
    echo -e "5) ${CYAN}Enable SELinux or AppArmor${NC}"
    echo -e "6) ${RED}Disk Encryption (Caution)${NC}"
    echo -e "7) ${CYAN}Backup System${NC}"
    echo -e "8) ${BLUE}Fix File Permissions and Ownership${NC}"
    echo -e "9) ${YELLOW}Setup SSH Key Authentication${NC}"
    echo -e "10) ${PURPLE}Install Auditd and Monitor${NC}"
    echo -e "0) ${GREEN}Exit${NC}"
    echo -e "${BOLD}${CYAN}================================${NC}"
}

# 12. Main Menu Loop
while true; do
    display_menu
    read -p "Choose an option: " choice
    case "$choice" in
        1) update_system ;;
        2) manage_users ;;
        3) configure_firewall ;;
        4) install_fail2ban ;;
        5) 
            echo -e "${YELLOW}Choose your security tool:${NC}"
            echo -e "1) ${RED}Enable SELinux (RedHat-based)${NC}"
            echo -e "2) ${CYAN}Enable AppArmor (Debian-based)${NC}"
            read -p "Enter choice: " sec_choice
            if [ "$sec_choice" -eq 1 ]; then
                enable_selinux
            elif [ "$sec_choice" -eq 2 ]; then
                enable_apparmor
            else
                echo -e "${RED}Invalid choice.${NC}"
            fi
            ;;
        6) enable_encryption ;;
        7) backup_system ;;
        8) fix_permissions ;;
        9) setup_ssh_key_auth ;;
        10) install_auditd ;;
        0) exit 0 ;;
        *) echo -e "${RED}Invalid choice, please select a valid option.${NC}" ;;
    esac
    read -p "Press [Enter] to continue..."  
done
