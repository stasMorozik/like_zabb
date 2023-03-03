import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import Ajv, {JSONSchemaType} from "ajv";
import addFormats from "ajv-formats";
import { Domain } from "../common/errors/domain";
import { Error } from "../common/errors/error";

export namespace Registration {
  export namespace Ports {
    export interface Api {
      fetch(dto: Dto): Observable<Either<Error[], Success>>
    }

    export interface Emiter {
      emit(either: Either<Error[], Success>): void
    }
  }

  export namespace Errors {
    export class PasswordNotEqual implements Error {
      constructor(
        readonly message: string
      ){}
    }
  }

  export type Dto = {
    name: string
    email: string
    password: string
    confirmPassword: string
  }

  export class Success {
    constructor(
      readonly message: string
    ){}
  }

  export class Validator {
    private readonly _validate: any;
    private readonly _ajv: Ajv;

    constructor(
    ){
      this._ajv = new Ajv();
      addFormats(this._ajv, ["email", "password"]);
      this._ajv.addFormat("name", /^[a-zA-Z\s]+$/);
      this._ajv.addFormat("password", /^[^а-яА-яЁё\s_]+$/);
      this._validate = this._ajv.compile(this.schema);
    }

    private readonly schema: JSONSchemaType<Dto> = {
      type: "object",
      properties: {
        name: {
          type: "string",
          format: "name"
        },
        email: {
          type: "string",
          format: 'email'
        },
        password: {
          type: "string",
          format: "password"
        },
        confirmPassword: {
          type: "string",
          format: "password"
        }
      },
      required: ["name", "email", "password", "confirmPassword"],
      additionalProperties: false
    }

    valid(dto: Dto): Either<Error[], Success> {
      if (!this._validate(dto)) {
        return left(this._validate.errors.map((errorObject: any) => {
          if (errorObject.instancePath == '/email') {
            return new Domain.InvalidEmail('Invalid email');
          }

          if (errorObject.instancePath == '/password') {
            return new Domain.InvalidPassword('Invalid password');
          }

          if (errorObject.instancePath == '/name') {
            return new Domain.InvalidName('Invalid name');
          }
        }));
      }

      if (dto.password != dto.confirmPassword) {
        return left([new Errors.PasswordNotEqual('Passwords are not equal')]);
      }

      return right(new Success('Successfully'));
    }
  }

  export class UseCase {
    private readonly _validator: Validator;
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){
      this._validator = new Validator();
    }

    registry(dto: Dto) {
      const maybeValid = this._validator.valid(dto);
      if (maybeValid.isLeft()) {
        this._emiter.emit(maybeValid)
      }

      if (maybeValid.isRight()) {
        this._api.fetch(dto).subscribe((maybeRight: Either<Error[], Success>) => {
          this._emiter.emit(maybeRight);
        });
      }
    }
  }
}
