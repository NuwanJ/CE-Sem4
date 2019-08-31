@ ARM Assembly - exercise 4
 
@ Written		: Dilshani
@ LastUpdate	: 2018-11-05

    .text     @ instruction memory
    
    
@ Write YOUR CODE HERE    

@	long factorial(int n)
@	{
@	  if (n == 0)
@	    return 1;
@	  else
@	    return(n * factorial(n-1));
@	}


@ ---------------------    


fact:    
		SUB sp,sp,#12    @ stack for 3 items
	    STR lr,[sp,#8] @ return address
	    STR r1,[sp,#4] @ argument n-1
	    STR r2,[sp,#0] @ argument n

    	CMP r0,#0 
    	BNE else

if:		MOV r0,#1
    	b endif

else:   SUB r1,r0,#1 	@ r1 = r0 - 1

		MOV r2, r0		@ r2 = n
		
		MOV r0, r1		@ r0 = n-1
		BL fact 		@ r0 = fact(n-1);
		MOV r1, r0		@ r1 = n-1

    	MUL r0,r1,r2 	@ r0 = r1 * r2   (n * fact(n-1))

endif:	
    	LDR r2,[sp,#0]
    	LDR r1,[sp,#4]
    	LDR lr,[sp,#8]
    	ADD sp,sp,#12
    
    	MOV pc,lr



@ ---------------------   

.global main
main:
    @ stack handling, will discuss later
    @ push (store) lr to the stack
    sub sp, sp, #4
    str lr, [sp, #0]

    mov r4, #8     @the value n

    @ calling the fact function
    mov r0, r4     @the arg1 load
    bl fact
    mov r5,r0
    

    @ load aguments and print
    ldr r0, =format
    mov r1, r4
    mov r2, r5
    bl printf

    @ stack handling (pop lr from the stack) and return
    ldr lr, [sp, #0]
    add sp, sp, #4
    mov pc, lr

    .data    @ data memory
format: .asciz "Factorial of %d is %d\n"

