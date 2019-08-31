@ ARM Assembly - exercise 6
@ 
@ Roshan Ragel - roshanr@pdn.ac.lk
@ Hasindu Gamaarachchi - hasindu@ce.pdn.ac.lk


@ Written       : Dilshani
@ LastUpdate    : 2018-11-05

    .text     @ instruction memory

    
@ Write YOUR CODE HERE    

@ ---------------------    
@Implement gcd subroutine to find gcd of arg1 and arg2
@ gcd:
@ ---------------------------------------


@ ----- Begin of gcd(x,y) -------------------------------------------------

gcd:    SUB sp,sp,#8

        @ r0 = x
        @ r1 = y

WHILE:  CMP r0,r1       
        BEQ EX_WHILE        @ if x==y call return procedure 
                            @ if x!=y while loop
        BLE Else            
        BGT If
  
        If:     SUB r0,r0,r1    @ x = x-y
                B WHILE

        Else:   SUB r1,r1,r0    @ y = y-x 
                B WHILE

EX_WHILE:   
        
        @ return procedure
        ADD sp,sp,#8
        MOV pc,lr

@ ----- End of gcd -------------------------------------------------








@ ---------------------    

    .global main
main:
    @ stack handling, will discuss later
    @ push (store) lr to the stack
    sub sp, sp, #4
    str lr, [sp, #0]

    mov r4, #64     @the value a
    mov r5, #24     @the value b
    

    @ calling the gcd function
    mov r0, r4     @the arg1 load
    mov r1, r5     @the arg2 load
    bl gcd
    mov r6,r0
    

    @ load aguments and print
    ldr r0, =format
    mov r1, r4
    mov r2, r5
    mov r3, r6
    bl printf

    @ stack handling (pop lr from the stack) and return
    ldr lr, [sp, #0]
    add sp, sp, #4
    mov pc, lr

    .data    @ data memory
format: .asciz "gcd(%d,%d) = %d\n"


